@extends('layouts.admin-layout')

@section('title', 'Cashflow Report')

@section('content')
<div class="card shadow-sm border-0">
	<div class="card-header bg-white">
		<h3 class="card-title"><i class="fas fa-cash-register me-2 text-success"></i>Cashflow Report</h3>
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

		<canvas id="cashflowChart" height="120" class="mt-4 mb-4"></canvas>

		<div class="alert alert-success d-flex align-items-center">
			<i class="fas fa-arrow-down me-2"></i>
			<strong>Total Income:</strong>&nbsp; Rp <span id="totalIncome">{{ $totalIncome }}</span>
		</div>
		<div class="alert alert-danger d-flex align-items-center">
			<i class="fas fa-arrow-up me-2"></i>
			<strong>Total Expense:</strong>&nbsp; Rp <span id="totalExpense">{{ $totalExpense }}</span>
		</div>

		<table class="table table-hover table-bordered mt-3" id="cashflowTable">
			<thead class="table-light">
				<tr>
					<th>#</th>
					<th><i class="fas fa-heading"></i> Title</th>
					<th><i class="fas fa-align-left"></i> Description</th>
					<th><i class="fas fa-money-bill-wave"></i> Nominal</th>
					<th><i class="fas fa-exchange-alt"></i> Type</th>
					<th><i class="fas fa-calendar-day"></i> Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($cashflows as $cashflow)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $cashflow->title }}</td>
					<td>{{ $cashflow->desc }}</td>
					<td>Rp {{ number_format($cashflow->nominal, 2, ',', '.') }}</td>
					<td>{{ ucfirst($cashflow->type) }}</td>
					<td>{{ $cashflow->date }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@push('scripts')
<script>
	$(document).ready(function() {
		// Inisialisasi data chart dari PHP
		const chartLabels = JSON.parse('{!! json_encode($labels ?? []) !!}');
		const chartIncome = JSON.parse('{!! json_encode($dataIncome ?? []) !!}');
		const chartExpense = JSON.parse('{!! json_encode($dataExpense ?? []) !!}');

		var ctx = document.getElementById('cashflowChart').getContext('2d');
		var cashflowChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: chartLabels,
				datasets: [{
						label: 'Income',
						data: chartIncome,
						backgroundColor: 'rgba(75, 192, 192, 0.2)',
						borderColor: 'rgba(75, 192, 192, 1)',
						borderWidth: 2,
						tension: 0.4,
						fill: true
					},
					{
						label: 'Expense',
						data: chartExpense,
						backgroundColor: 'rgba(255, 99, 132, 0.2)',
						borderColor: 'rgba(255, 99, 132, 1)',
						borderWidth: 2,
						tension: 0.4,
						fill: true
					}
				]
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

		// Filter button click event
		$('#filter').click(function() {
			var period = $('#period').val();
			var month = $('#month').val();
			var year = $('#year').val();

			$.ajax({
				url: '{{ route("report.cashflow") }}',
				type: 'GET',
				data: { period, month, year },
				success: function(response) {
					cashflowChart.data.labels = response.labels;
					cashflowChart.data.datasets[0].data = response.dataIncome;
					cashflowChart.data.datasets[1].data = response.dataExpense;
					cashflowChart.update();

					$('#totalIncome').text(response.totalIncome);
					$('#totalExpense').text(response.totalExpense);

					var tableBody = '';
					response.cashflows.forEach(function(cashflow, index) {
						tableBody += `
							<tr>
								<td>${index + 1}</td>
								<td>${cashflow.title}</td>
								<td>${cashflow.desc}</td>
								<td>Rp ${cashflow.nominal.toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
								<td>${cashflow.type.charAt(0).toUpperCase() + cashflow.type.slice(1)}</td>
								<td>${cashflow.date}</td>
							</tr>
						`;
					});
					$('#cashflowTable tbody').html(tableBody);
				}
			});
		});
	});
</script>
@endpush
@endsection
