@extends('layouts.admin-layout')

@section('title', 'Edit Member')

@section('content')
<div class="card shadow-sm rounded">
	<div class="card-header bg-primary text-white">
		<h3 class="card-title mb-0"><i class="fas fa-edit mr-2"></i> Edit Member</h3>
	</div>

	<form id="editForm" action="{{ route('member.update', $member->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		@method('PUT')

		<div class="card-body">
			<!-- Email -->
			<div class="form-group mb-3">
				<label for="email" data-bs-toggle="tooltip" title="Email anggota yang digunakan untuk login.">
					<i class="fas fa-envelope text-secondary mr-1"></i> Email
				</label>
				<input type="email" name="email" value="{{ $member->user->email }}"
					class="form-control @error('email') is-invalid @enderror" id="email"
					placeholder="Masukkan email" required>
				@error('email')
				<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<!-- Password -->
			<div class="form-group mb-3">
				<label for="password" data-bs-toggle="tooltip" title="Kosongkan jika tidak ingin mengubah password.">
					<i class="fas fa-lock text-secondary mr-1"></i> Password <small>(Kosongkan jika tidak diubah)</small>
				</label>
				<input type="password" name="password"
					class="form-control @error('password') is-invalid @enderror" id="password"
					placeholder="Masukkan password baru">
				@error('password')
				<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<!-- Profile Image -->
			<div class="form-group mb-3">
				<label for="image" data-bs-toggle="tooltip" title="Upload gambar profil anggota.">
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

			@if ($member->user->image_path)
			<div id="currentImagePreview" class="mt-3">
				<p class="text-muted"><i class="fas fa-image text-secondary mr-1"></i> Gambar Saat Ini</p>
				<img src="{{ asset('storage/' . $member->user->image_path) }}" alt="Current Image" class="img-thumbnail shadow-sm"
					style="max-width: 200px; max-height: 200px;">
			</div>
			@endif

			<div id="updatedImagePreview" class="mt-3" style="display: none;">
				<p class="text-muted"><i class="fas fa-eye text-secondary mr-1"></i> Pratinjau Gambar Baru</p>
				<img id="previewImage" src="#" alt="Preview Image" class="img-thumbnail shadow-sm"
					style="max-width: 200px; max-height: 200px;">
			</div>

			<!-- Name -->
			<div class="form-group mb-3">
				<label for="name" data-bs-toggle="tooltip" title="Nama lengkap anggota.">
					<i class="fas fa-user text-secondary mr-1"></i> Nama
				</label>
				<input type="text" name="name" value="{{ $member->name }}"
					class="form-control @error('name') is-invalid @enderror" id="name"
					placeholder="Masukkan nama lengkap" required>
				@error('name')
				<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>

			<!-- Phone Number -->
			<div class="form-group mb-3">
				<label for="phone_number" data-bs-toggle="tooltip" title="Nomor telepon anggota.">
					<i class="fas fa-phone text-secondary mr-1"></i> Nomor Telepon
				</label>
				<input type="text" name="phone_number" value="{{ $member->phone_number }}"
					class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
					placeholder="Masukkan nomor telepon" required>
				@error('phone_number')
				<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="card-footer text-end">
			<a href="{{ route('member.index') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Kembali</a>
			<button type="button" id="btnSubmit" class="btn btn-success"><i class="fas fa-check"></i> Simpan Perubahan</button>
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
				$('#currentImagePreview').hide();
			}
			if (this.files[0]) {
				reader.readAsDataURL(this.files[0]);
			}
		});

		$('[data-bs-toggle="tooltip"]').tooltip();

		// Konfirmasi SweetAlert sebelum submit
		$('#btnSubmit').click(function (e) {
			e.preventDefault();
			Swal.fire({
				title: 'Simpan Perubahan?',
				text: "Perubahan pada member akan disimpan.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, simpan',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$('#editForm').submit();
				}
			});
		});
	});
</script>
@endpush
