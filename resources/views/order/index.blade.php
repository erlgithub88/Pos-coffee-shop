@extends('layouts.admin-layout')

@section('title', 'Order')

@section('content')

@push('styles')
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<style>
	body {
		font-family: 'Inter', sans-serif;
	}

	.table-hover tbody tr:hover {
		background-color: #e0f2fe;
		/* biru muda soft */
		transition: background-color 0.3s ease;
	}

	.table thead th {
		background-color: #f5e9da;
		/* biru terang */
		color: #fff;
		font-weight: 600;
		border: none;
	}

	.table {
		border-radius: 0.5rem;
		overflow: hidden;
		box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
	}

	.btn-primary {
		background-color: #2563eb;
		border-color: #2563eb;
		font-weight: 600;
	}

	.btn-primary:hover {
		background-color: #1d4ed8;
	}

	.action-btn {
		margin-right: 5px;
	}

	.action-btn i {
		pointer-events: none;
	}
</style>
@endpush

<div class="card shadow-sm rounded">
	@if (Auth::user()->role == 'cashier')
	<div class="card-header bg-primary text-white">
		<div class="d-flex justify-content-between align-items-center w-100">
			<h3 class="card-title mb-0">
				<i class="fas fa-receipt mr-2"></i> Daftar Order
			</h3>
			<a href="{{ route('order.create') }}" class="btn btn-success shadow-sm px-4 py-2">
				<i class="fas fa-plus-circle mr-1"></i> Buat Order
			</a>
		</div>
	</div>
	@endif

	<div class="card-body">
		@if(session('error'))
		<div class="alert alert-danger">
			<i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
		</div>
		@endif
		@if(session('success'))
		<div class="alert alert-success">
			<i class="fas fa-check-circle"></i> {{ session('success') }}
		</div>
		@endif

		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover datatable">
				<thead>
					<tr>
						<th>#</th>
						<th><i class="fas fa-user-tag mr-1"></i> Kasir</th>
						<th><i class="fas fa-calendar-day mr-1"></i> Tanggal</th>
						<th><i class="fas fa-credit-card mr-1"></i> Metode Bayar</th>
						<th><i class="fas fa-money-bill-wave mr-1"></i> Total Harga</th>
						<th><i class="fas fa-boxes mr-1"></i> Total Item</th>
						<th><i class="fas fa-cogs mr-1"></i> Aksi</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(function() {
		$('.datatable').DataTable({
			serverSide: true,
			processing: true,
			ajax: '{{ route("order.index") }}',
			columns: [{
					data: 'DT_RowIndex',
					orderable: false,
					searchable: false
				},
				{
					data: 'cashier_name'
				},
				{
					data: 'date'
				},
				{
					data: 'payment_method_name'
				},
				{
					data: 'total_price',
					render: function(data) {
						return 'Rp' + parseFloat(data).toLocaleString('id-ID', {
							minimumFractionDigits: 2
						});
					}
				},
				{
					data: 'total_items'
				},
				{
					data: 'action',
					orderable: false,
					searchable: false,
					render: function(data, type, row) {
						return `
		<a href="/order/${row.id}" class="btn btn-sm btn-info action-btn" title="Detail">
			<i class="fas fa-eye"></i>
		</a>
		<a href="/order/${row.id}/print" target="_blank" class="btn btn-sm btn-secondary action-btn" title="Cetak Struk">
			<i class="fas fa-print"></i>
		</a>
		<button onclick="confirmDelete(${row.id})" class="btn btn-sm btn-danger action-btn" title="Hapus">
			<i class="fas fa-trash-alt"></i>
		</button>
		<form id="delete-order-${row.id}" action="/order/${row.id}" method="POST" style="display:none;">
			@csrf
			@method('DELETE')
		</form>
	`;
					}
				},
			],
			responsive: true,
			autoWidth: false,
			language: {
				searchPlaceholder: "Cari order...",
				search: "",
			}
		});
	});

	function confirmDelete(orderId) {
		Swal.fire({
			title: 'Hapus Order?',
			text: "Order yang dihapus tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#6c757d',
			confirmButtonText: '<i class="fas fa-trash-alt"></i> Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				document.getElementById('delete-order-' + orderId).submit();
			}
		});
	}
</script>
@endpush

@endsection