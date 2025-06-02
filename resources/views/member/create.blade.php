@extends('layouts.admin-layout')

@section('title', 'Create Member')

@section('content')
<div class="card shadow-sm rounded">
	<div class="card-header bg-primary text-white">
		<h3 class="card-title mb-0"><i class="fas fa-user-plus mr-2"></i> Tambah Member Baru</h3>
	</div>

	<form id="createForm" action="{{ route('member.store') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="card-body">
			@if(session('error'))
				<div class="alert alert-danger">{{ session('error') }}</div>
			@endif
			@if(session('success'))
				<div class="alert alert-success">{{ session('success') }}</div>
			@endif

			<div class="form-group mb-3">
				<label for="email" data-bs-toggle="tooltip" title="Email untuk login member.">
					<i class="fas fa-envelope text-secondary mr-1"></i> Email
				</label>
				<input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
					id="email" placeholder="Contoh: member@mail.com" required>
				@error('email')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="form-group mb-3">
				<label for="password" data-bs-toggle="tooltip" title="Password untuk login member.">
					<i class="fas fa-lock text-secondary mr-1"></i> Password
				</label>
				<input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
					id="password" placeholder="Minimal 6 karakter" required>
				@error('password')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="form-group mb-3">
				<label for="name" data-bs-toggle="tooltip" title="Nama lengkap member.">
					<i class="fas fa-id-card text-secondary mr-1"></i> Nama
				</label>
				<input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
					id="name" placeholder="Masukkan nama lengkap" required>
				@error('name')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="form-group mb-3">
				<label for="phone_number" data-bs-toggle="tooltip" title="Nomor telepon aktif member.">
					<i class="fas fa-phone text-secondary mr-1"></i> Nomor Telepon
				</label>
				<input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
					id="phone_number" placeholder="Contoh: 081234567890" required>
				@error('phone_number')
					<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<div class="form-group mb-3">
				<label for="image" data-bs-toggle="tooltip" title="Upload foto profil member.">
					<i class="fas fa-image text-secondary mr-1"></i> Foto Profil
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
			<a href="{{ route('member.index') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Kembali</a>
			<button type="button" id="btnSubmit" class="btn btn-success"><i class="fas fa-check"></i> Simpan Member</button>
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
				title: 'Simpan Member Baru?',
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
