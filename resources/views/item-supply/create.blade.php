@extends('layouts.admin-layout')

@section('title', 'Create Item Supply')

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
<style>
	.card-title i { margin-right: 8px; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0 rounded">
	<div class="card-header bg-primary text-white">
		<h3 class="card-title"><i class="fas fa-plus-circle"></i> Tambah Item Supply</h3>
	</div>

	<form id="itemSupplyForm" action="{{ route('item-supply.store') }}" method="post">
		@csrf
		<div class="card-body">
			<div class="form-group">
				<label for="item_id"><i class="fas fa-box-open text-secondary mr-1"></i> Pilih Item</label>
				<select name="item_id" class="form-control select2 @error('item_id') is-invalid @enderror" style="width: 100%;" data-placeholder="Pilih item...">
					<option value=""></option>
					@foreach($items as $item)
						<option value="{{ $item->id }}">{{ $item->name }}</option>
					@endforeach
				</select>
				@error('item_id')
					<p class="text-danger mt-1">{{ $message }}</p>
				@enderror
			</div>

			<div class="form-group">
				<label for="qty"><i class="fas fa-sort-numeric-up-alt text-secondary mr-1"></i> Jumlah</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-cubes"></i></span>
					</div>
					<input type="number" name="qty" id="qty" class="form-control @error('qty') is-invalid @enderror" placeholder="Masukkan jumlah">
				</div>
				@error('qty')
					<p class="text-danger mt-1">{{ $message }}</p>
				@enderror
			</div>
		</div>

		<div class="card-footer d-flex justify-content-end bg-white">
			<a href="{{ route('item-supply.index') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Kembali</a>
			<button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Simpan</button>
		</div>
	</form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<script>
	$(document).ready(function () {
		$('.select2').select2({
			theme: 'bootstrap4',
			placeholder: function() {
				return $(this).data('placeholder');
			}
		});
	});

	document.getElementById('itemSupplyForm').addEventListener('submit', function (e) {
		e.preventDefault();
		const form = this;
		const item = form.item_id.value.trim();
		const qty = form.qty.value.trim();

		if (!item || !qty) {
			Swal.fire({
				icon: 'warning',
				title: 'Form belum lengkap',
				text: 'Mohon lengkapi semua isian.',
				timer: 2500,
				timerProgressBar: true,
				showConfirmButton: false,
			});
			return;
		}

		Swal.fire({
			title: 'Yakin ingin menyimpan supply item ini?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#2563eb',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Ya, simpan',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				form.submit();
			}
		});
	});
</script>
@endpush
