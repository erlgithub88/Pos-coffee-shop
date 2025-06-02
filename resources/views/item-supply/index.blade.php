@extends('layouts.admin-layout')

@section('title', 'Item Supply')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
	.table-hover tbody tr:hover {
		background-color: #f0f8ff;
		transition: background-color 0.3s;
	}
</style>
@endpush

<div class="card shadow-sm rounded">
	<div class="card-header bg-primary text-white">
		<div class="d-flex justify-content-between align-items-center w-100">
			<h3 class="card-title mb-0">
				<i class="fas fa-truck-loading mr-2"></i> Daftar Item Supply
			</h3>

			@if (Auth::user()->role == 'manager')
			<a href="{{ route('item-supply.create') }}" class="btn btn-success shadow-sm px-4 py-2">
				<i class="fas fa-plus-circle mr-1"></i> Tambah Supply
			</a>
			@endif
		</div>
	</div>

	<div class="card-body">
		<table class="table table-bordered table-striped table-hover datatable">
			<thead class="thead-light">
				<tr>
					<th><i class="fas fa-hashtag"></i></th>
					<th><i class="fas fa-user-tie"></i> Manager</th>
					<th><i class="fas fa-box"></i> Nama Item</th>
					<th><i class="fas fa-cubes"></i> Jumlah</th>
					<th><i class="fas fa-calendar-day"></i> Tanggal</th>
				</tr>
			</thead>
		</table>
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
<script>
	$(function() {
		$('.datatable').DataTable({
			serverSide: true,
			processing: true,
			ajax: '{{ route("item-supply.index") }}',
			columns: [{
					data: 'DT_RowIndex',
					orderable: false,
					searchable: false
				},
				{
					data: 'manager_name',
					orderable: false
				},
				{
					data: 'item_name'
				},
				{
					data: 'qty',
					orderable: false,
					searchable: false,
					render: function(data) {
						return `<span class="badge badge-info"><i class="fas fa-boxes mr-1"></i>${data}</span>`;
					}
				},
				{
					data: 'date',
					searchable: false
				},
			]
		});
	});
</script>
@endpush
@endsection