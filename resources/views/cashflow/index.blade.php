@extends('layouts.admin-layout')

@section('title', 'Cashflow')

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
		background-color: #e0f2fe; /* biru muda soft */
		transition: background-color 0.3s ease;
	}

	.table thead th {
		background-color: #3b82f6; /* biru terang */
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
	@if (Auth::user()->role == 'manager')
	<div class="card-header bg-primary text-white">
		<div class="d-flex justify-content-between align-items-center w-100">
			<h3 class="card-title mb-0"><i class="fas fa-wallet mr-2"></i> Daftar Cashflow</h3>
			<a href="{{ route('cashflow.create') }}" class="btn btn-success shadow-sm px-4 py-2">
				<i class="fas fa-plus-circle mr-1"></i> Create Cashflow
			</a>
		</div>
	</div>
	@else
		<div class="card-header bg-primary text-white">
			<h3 class="card-title mb-0"><i class="fas fa-wallet mr-2"></i> Daftar Cashflow</h3>
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
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th><i class="fas fa-user-tie mr-1"></i> Manager</th>
						<th><i class="fas fa-heading mr-1"></i> Title</th>
						<th><i class="fas fa-align-left mr-1"></i> Description</th>
						<th><i class="fas fa-money-bill-wave mr-1"></i> Nominal</th>
						<th><i class="fas fa-list-alt mr-1"></i> Type</th>
						<th><i class="fas fa-calendar-alt mr-1"></i> Date</th>
						<th><i class="fas fa-cogs mr-1"></i> Action</th>
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
	$(function () {
		$('.datatable').DataTable({
			serverSide: true,
			processing: true,
			ajax: '{{ route("cashflow.index") }}',
			columns: [
				{ data: 'DT_RowIndex', orderable: false, searchable: false },
				{ data: 'manager_name' },
				{ data: 'title' },
				{ data: 'desc', orderable: false },
				{
					data: 'nominal',
					render: data => 'Rp' + parseFloat(data).toLocaleString('id-ID', {
						minimumFractionDigits: 2,
						maximumFractionDigits: 2
					})
				},
				{
					data: 'type',
					searchable: false,
					render: data => data.charAt(0).toUpperCase() + data.slice(1)
				},
				{ data: 'date' },
				{
					data: 'action',
					orderable: false,
					searchable: false,
					render: function(data, type, row) {
						return `
							<a href="/cashflow/${row.id}/edit" class="btn btn-sm btn-info action-btn" title="Edit">
								<i class="fas fa-edit"></i>
							</a>
							<button onclick="confirmDelete(${row.id})" class="btn btn-sm btn-danger action-btn" title="Hapus">
								<i class="fas fa-trash-alt"></i>
							</button>
							<form id="delete-form-${row.id}" action="/cashflow/${row.id}" method="POST" style="display:none;">
								@csrf
								@method('DELETE')
							</form>
						`;
					}
				},
			],
			responsive: true,
			autoWidth: false,
			lengthMenu: [10, 25, 50],
			language: {
				searchPlaceholder: "Cari cashflow...",
				search: "",
			}
		});
	});

	function confirmDelete(cashflowId) {
		Swal.fire({
			title: 'Hapus Cashflow?',
			text: "Data yang dihapus tidak dapat dikembalikan!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#6c757d',
			confirmButtonText: '<i class="fas fa-trash-alt"></i> Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				document.getElementById('delete-form-' + cashflowId).submit();
			}
		});
	}
</script>
@endpush

@endsection
