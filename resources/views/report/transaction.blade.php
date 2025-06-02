@extends('layouts.admin-layout')

@section('title', 'Transaction Report')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h3 class="card-title"><i class="fas fa-chart-line me-2 text-primary"></i>Transaction Report</h3>
    </div>
    <div class="card-body">
        <div class="form-group mb-4">
            <label><i class="fas fa-calendar-alt me-1 text-secondary"></i>Select Period:</label>
            <select id="period" class="form-control">
                <option value="monthly" selected>Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="month"><i class="far fa-calendar-alt me-1 text-secondary"></i>Month:</label>
                    <select id="month" class="form-control">
                        <option value="">--Select Month--</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="year"><i class="fas fa-calendar me-1 text-secondary"></i>Year:</label>
                    <input type="number" id="year" class="form-control" value="{{ date('Y') }}" placeholder="Year">
                </div>
                <div class="col-md-12 mt-3">
                    <button id="filter" class="btn btn-primary w-100 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
        </div>

        <div id="loadingSpinner" class="text-center mt-3 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading data...</p>
        </div>

        <div class="col-md-12 mt-3">
            <a href="#" id="exportBtn" class="btn btn-success w-100 shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Export to Excel
            </a>
        </div>

        <div class="col-md-12 mt-2">
            <a href="#" id="exportPdfBtn" class="btn btn-danger w-100 shadow-sm">
                <i class="fas fa-file-pdf me-1"></i> Export to PDF
            </a>
        </div>

        <canvas id="transactionChart" height="120" class="mt-4 mb-4"></canvas>

        <div class="alert alert-info d-flex align-items-center">
            <i class="fas fa-wallet me-2"></i>
            <strong>Total Profit:</strong>&nbsp; Rp <span id="totalProfit">{{ $total_profit }}</span>
        </div>

        <table class="table table-hover table-bordered mt-3" id="ordersTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th><i class="fas fa-user"></i> Cashier</th>
                    <th><i class="fas fa-calendar-day"></i> Date</th>
                    <th><i class="fas fa-money-bill-wave"></i> Payment</th>
                    <th><i class="fas fa-tags"></i> Total Price</th>
                    <th><i class="fas fa-coins"></i> Total Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->cashier->name }}</td>
                    <td>{{ $order->date }}</td>
                    <td>{{ $order->paymentMethod->method }}</td>
                    <td>Rp
                        {{ number_format($order->orderDetails->sum(fn($d) => $d->selling_price * $d->qty), 2, ',', '.') }}
                    </td>
                    <td>Rp
                        {{ number_format($order->orderDetails->sum(fn($d) => ($d->selling_price - $d->capital_price) * $d->qty), 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        var ctx = document.getElementById('transactionChart').getContext('2d');
        const chartLabels = JSON.parse('{!! json_encode($labels ?? []) !!}');
        const chartData = JSON.parse('{!! json_encode($data ?? []) !!}');

        var transactionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Transactions',
                    data: chartData,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#333',
                            font: { size: 14 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Rp',
                            color: '#555'
                        }
                    }
                }
            }
        });

        function showLoading(show = true) {
            if (show) {
                $('#loadingSpinner').removeClass('d-none');
                $('#filter, #exportBtn, #exportPdfBtn').attr('disabled', true);
            } else {
                $('#loadingSpinner').addClass('d-none');
                $('#filter, #exportBtn, #exportPdfBtn').attr('disabled', false);
            }
        }

        $('#filter').click(function () {
            const period = $('#period').val();
            const month = $('#month').val();
            const year = $('#year').val();

            if (!year || (period === 'monthly' && !month)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please select both month and year.'
                });
                return;
            }

            showLoading(true);

            $.ajax({
                url: '{{ route("report.transaction") }}',
                type: 'GET',
                data: { period, month, year },
                success: function (response) {
                    transactionChart.data.labels = response.labels;
                    transactionChart.data.datasets[0].data = response.data;
                    transactionChart.update();

                    let tableBody = '';
                    response.orders.forEach((order, index) => {
                        tableBody += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${order.cashier.name}</td>
                                <td>${order.date}</td>
                                <td>${order.payment_method.method}</td>
                                <td>Rp ${order.total_price}</td>
                                <td>Rp ${order.total_profit}</td>
                            </tr>`;
                    });
                    $('#ordersTable tbody').html(tableBody);
                    $('#totalProfit').text(response.total_profit);
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load data.'
                    });
                },
                complete: function () {
                    showLoading(false);
                }
            });
        });

        $('#exportBtn').click(function (e) {
            e.preventDefault();
            const period = $('#period').val();
            const month = $('#month').val();
            const year = $('#year').val();

            if (!year || (period === 'monthly' && !month)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please select both month and year before exporting.'
                });
                return;
            }

            const url = `{{ route('report.transaction.export') }}?period=${period}&month=${month}&year=${year}`;
            window.location.href = url;
        });

        $('#exportPdfBtn').click(function (e) {
            e.preventDefault();
            const period = $('#period').val();
            const month = $('#month').val();
            const year = $('#year').val();

            if (!year || (period === 'monthly' && !month)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please select both month and year before exporting.'
                });
                return;
            }

            const url = `{{ route('report.export.pdf') }}?period=${period}&month=${month}&year=${year}`;
            window.location.href = url;
        });
    });
</script>
@endpush
