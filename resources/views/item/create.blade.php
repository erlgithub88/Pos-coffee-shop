@extends('layouts.admin-layout')

@section('title', 'Create Item')

@section('content')
<div class="card shadow-sm rounded">
	<div class="card-header bg-primary text-white">
		<h3 class="card-title mb-0"><i class="fas fa-box-open mr-2"></i> Tambah Item Baru</h3>
	</div>

	<form id="createForm" action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="card-body">
			<div class="form-group mb-3">
				<label for="name" data-bs-toggle="tooltip" title="Nama item yang akan ditampilkan di POS.">
					<i class="fas fa-tag text-secondary mr-1"></i> Nama Item
				</label>
				<input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
					id="name" placeholder="Contoh: Kopi Arabika 1kg" required>
				@error('name')
				<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="form-group mb-3">
				<label for="desc" data-bs-toggle="tooltip" title="Deskripsi singkat tentang item.">
					<i class="fas fa-align-left text-secondary mr-1"></i> Deskripsi
				</label>
				<textarea class="form-control @error('desc') is-invalid @enderror" name="desc" id="desc" rows="3"
					placeholder="Masukkan deskripsi item..." required></textarea>
				@error('desc')
				<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group mb-3">
						<label for="capitalPrice" data-bs-toggle="tooltip" title="Harga beli/modal dari item.">
							<i class="fas fa-coins text-secondary mr-1"></i> Harga Modal
						</label>
						<input type="number" name="capital_price" class="form-control @error('capital_price') is-invalid @enderror"
							id="capitalPrice" placeholder="Contoh: 15000" required>
						@error('capital_price')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group mb-3">
						<label for="sellingPrice" data-bs-toggle="tooltip" title="Harga jual item ke pelanggan.">
							<i class="fas fa-money-bill-wave text-secondary mr-1"></i> Harga Jual
						</label>
						<input type="number" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
							id="sellingPrice" placeholder="Contoh: 20000" required>
						@error('selling_price')
						<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
				</div>
			</div>

			<div class="form-group mb-3">
				<label for="image" data-bs-toggle="tooltip" title="Upload gambar item untuk tampilan visual.">
					<i class="fas fa-image text-secondary mr-1"></i> Gambar Produk
				</label>
				<div class="input-group">
					<div class="custom-file">
						<input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" id="image">
						<label class="custom-file-label" for="image">Pilih Gambar</label>
					</div>
				</div>
				@error('image')
				<div class="text-danger">{{ $message }}</div>
				@enderror
			</div>

			<div id="updatedImagePreview" class="mt-3" style="display: none;">
				<p class="text-muted"><i class="fas fa-eye text-secondary mr-1"></i> Pratinjau Gambar</p>
				<img id="previewImage" src="#" alt="Preview Image" class="img-thumbnail shadow-sm"
					style="max-width: 200px; max-height: 200px;">
			</div>
		</div>

		<div class="card-footer text-end">
			<a href="{{ route('item.index') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Kembali</a>
			<button type="button" id="btnSubmit" class="btn btn-success"><i class="fas fa-check"></i> Simpan Item</button>
		</div>
	</form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(function () {
		bsCustomFileInput.init();

		$('#image').change(function () {
			let reader = new FileReader();
			reader.onload = function (e) {
				$('#previewImage').attr('src', e.target.result);
				$('#updatedImagePreview').fadeIn();
			}
			if (this.files[0]) {
				reader.readAsDataURL(this.files[0]);
			}
		});

		// Aktifkan tooltip Bootstrap
		$('[data-bs-toggle="tooltip"]').tooltip();

		// SweetAlert konfirmasi sebelum submit form
		$('#btnSubmit').click(function (e) {
			e.preventDefault();
			Swal.fire({
				title: 'Simpan Item Baru?',
				text: "Pastikan data sudah benar sebelum disimpan.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, simpan',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$('#createForm').submit();
				}
			});
		});
	});
</script>
@endpush
