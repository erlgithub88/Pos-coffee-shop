@extends('layouts.admin-layout')

@section('title', 'Create Order')

@section('content')
<div class="card card-primary shadow">
    <div class="card-header bg-success">
        <h3 class="card-title text-white"><i class="fas fa-mug-hot"></i> Buat Pesanan Baru</h3>
    </div>

    <form action="{{ route('order.store') }}" method="post">
        @csrf
        <div class="card-body">
            @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
            @endif

            <div class="form-group">
                <label class="font-weight-bold"><i class="fas fa-boxes"></i> Pilih Produk</label>
                <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#itemModal">
                    <i class="fas fa-search"></i> Cari Produk
                </button>

                <div id="selectedItemsContainer" class="row"></div>
                <div id="selectedItemsInputContainer"></div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <label for="discount"><i class="fas fa-percent"></i> Diskon</label>
                    <input type="number" name="discount" id="discount" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                    <label for="discount_type"><i class="fas fa-sliders-h"></i> Tipe Diskon</label>
                    <select name="discount_type" id="discount_type" class="form-control">
                        <option value="percentage">Persen (%)</option>
                        <option value="amount">Nominal (Rp)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="payment_method_id"><i class="fas fa-wallet"></i> Metode Pembayaran</label>
                    <select name="payment_method_id" id="payment_method_id" class="form-control">
                        @foreach ($paymentMethods as $paymentMethod)
                        <option value="{{ $paymentMethod->id }}">{{ ucfirst($paymentMethod->method) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mt-3">
                <label><i class="fas fa-money-bill-wave"></i> Total</label>
                <input type="text" id="totalPrice" class="form-control font-weight-bold text-success" readonly>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-mug-hot"></i> Buat Pesanan
            </button>
        </div>
    </form>
</div>

<!-- Modal Produk -->
<div class="modal fade" id="itemModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-search"></i> Pilih Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped datatable" id="itemsDataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Qty</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Import font Poppins dari Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">


<style>
    /* CARD CONTAINER: mengatur ukuran, tampilan, dan efek hover card */
    .selected-card {
        font-family: 'Poppins', sans-serif;
        /* Font modern dan clean */
        width: 300px;
        /* Lebar tetap sesuai gambar */
        max-width: 300px;
        background-color: #f9f9f9;
        /* Warna latar card */
        border-radius: 0.75rem;
        /* Sudut membulat */
        overflow: hidden;
        /* Agar isi tidak keluar batas */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        /* Bayangan halus */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        /* Animasi hover */
        margin: 1rem;
        /* Jarak antar card */
    }

    /* Efek saat mouse hover di atas card */
    .selected-card:hover {
        transform: translateY(-5px);
        /* Naik sedikit */
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        /* Bayangan lebih besar */
    }

    /* GAMBAR PRODUK */
    .selected-card img {
        width: 300px;
        /* Lebar gambar sesuai card */
        height: 300px;
        /* Tinggi gambar tetap */
        object-fit: cover;
        /* Gambar penuh area tanpa distorsi */
        border-top-left-radius: 0.75rem;
        /* Sudut atas kiri membulat */
        border-top-right-radius: 0.75rem;
        /* Sudut atas kanan membulat */
        margin-bottom: 0;
        /* Hilangkan jarak bawah */
    }

    /* BODY CARD (isi: nama, qty, harga, tombol) */
    .selected-card .card-body {
        display: flex;
        /* Gunakan Flexbox */
        flex-direction: column;
        /* Tata secara vertikal */
        align-items: center;
        /* Tengah secara horizontal */
        padding: 1.25rem;
        /* Ruang di dalam */
    }

    /* NAMA PRODUK */
    .selected-card .card-title {
        font-size: 1.80rem;
        /* Ukuran teks besar */
        font-weight: 700;
        /* Tebal (bold) */
        margin-top: 0.5rem;
        /* Jarak atas */
        margin-bottom: 0.5rem;
        /* Jarak bawah */
        text-align: center;
        /* Teks rata tengah */
        color: #333;
        /* Warna abu tua */
    }

    /* TEKS KETERANGAN (Qty, Harga) */
    .selected-card .card-text {
        margin-bottom: 0.25rem;
        /* Jarak antar baris */
        font-size: 1.15rem;
        /* Sedikit lebih besar */
        color: #555;
        /* Warna abu muda */
        font-weight: 400;
        /* Normal */
    }

    /* TOMBOL HAPUS ITEM */
    .removeItemBtn {
        width: 100%;
        /* Lebar penuh card */
        margin-top: 0.75rem;
        /* Jarak atas */
        background-color: #ff5c5c;
        /* Warna merah cerah */
        color: #fff;
        /* Teks putih */
        border: none;
        /* Hilangkan border default */
        padding: 0.5rem 1rem;
        /* Ukuran tombol */
        border-radius: 0.5rem;
        /* Tombol membulat */
        font-weight: 600;
        /* Teks agak tebal */
        transition: background-color 0.2s ease;
        /* Animasi saat hover */
        font-family: 'Poppins', sans-serif;
        /* Font konsisten */
    }

    /* Efek hover pada tombol hapus */
    .removeItemBtn:hover {
        background-color: #e04848;
        /* Warna lebih gelap saat hover */
        cursor: pointer;
        /* Tampilkan pointer saat hover */
    }
</style>

@endpush

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route("item.index") }}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name'
                },
                {
                    data: 'selling_price',
                    render: data => 'Rp' + parseFloat(data).toLocaleString('id-ID')
                },
                {
                    data: 'qty'
                },
                {
                    data: null,
                    render: (data, type, row) =>
                        `<input type="number" class="itemQty form-control form-control-sm" value="1" min="1" data-id="${row.id}">`
                },
                {
                    data: null,
                    render: (data, type, row) =>
                        `<button type="button" class="btn btn-success btn-sm selectItemBtn"
                        data-id="${row.id}"
                        data-name="${row.name}"
                        data-selling-price="${row.selling_price}"
                        data-image="${row.image_path || ''}">
                        Pilih
                    </button>`
                }
            ]
        });

        let selectedItems = {};
        $(document).on('click', '.selectItemBtn', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = parseFloat($(this).data('selling-price'));
            const qty = parseInt($('.itemQty[data-id="' + id + '"]').val());
            const image = $(this).data('image') || '';

            if (selectedItems[id]) {
                selectedItems[id].qty += qty;
            } else {
                selectedItems[id] = {
                    id,
                    name,
                    selling_price: price,
                    qty,
                    image_path: image
                };
            }
            updateSelectedItemsUI();
        });

        $(document).on('click', '.removeItemBtn', function() {
            delete selectedItems[$(this).data('id')];
            updateSelectedItemsUI();
        });

        function updateSelectedItemsUI() {
            const container = $('#selectedItemsContainer');
            container.empty();
            Object.values(selectedItems).forEach(item => {
                container.append(`
                <div class="col-md-4 mb-3">
                    <div class="card selected-card shadow">
                        ${item.image_path ? `<img src="/storage/${item.image_path}" alt="${item.name}" class="img-fluid">` : ''}
                        <div class="card-body">
                            <h5 class="card-title">${item.name}</h5>
                            <p class="card-text">Qty: <strong>${item.qty}</strong></p>
                            <p class="card-text">Harga: <strong>Rp${item.selling_price.toLocaleString('id-ID')}</strong></p>
                            <button type="button" class="btn btn-danger removeItemBtn" data-id="${item.id}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `);
            });

            $('#selectedItemsInputContainer').empty();
            Object.keys(selectedItems).forEach(id => {
                $('#selectedItemsInputContainer').append(`<input type="hidden" name="item_id[]" value="${id}">`);
            });

            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            Object.values(selectedItems).forEach(item => {
                total += item.selling_price * item.qty;
            });

            let discount = parseFloat($('#discount').val()) || 0;
            let type = $('#discount_type').val();
            if (type === 'percentage') {
                total -= total * discount / 100;
            } else {
                total -= discount;
            }

            $('#totalPrice').val('Rp' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        $(document).on('change', '#discount, #discount_type', calculateTotal);
    });
</script>
@endpush
@endsection