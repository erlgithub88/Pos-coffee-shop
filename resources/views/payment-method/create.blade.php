@extends('layouts.admin-layout')

@section('title', 'Create Payment Method')

@section('content')
<div class="card shadow-sm rounded">
	<div class="card-header bg-primary text-white">
		<h3 class="card-title mb-0"><i class="fas fa-credit-card mr-2"></i> Tambah Metode Pembayaran Baru</h3>
	</div>

	<form id="createForm" action="{{ route('payment-method.store') }}" method="POST">
		@csrf
		<div class="card-body">
			<div class="form-group mb-3">
				<label for="method" data-bs-toggle="tooltip" title="Nama metode pembayaran yang akan digunakan di POS.">
					<i class="fas fa-money-check-alt text-secondary mr-1"></i> Metode Pembayaran
				</label>
				<input type="text" name="method" class="form-control @error('method') is-invalid @enderror"
					id="method" placeholder="Contoh: Transfer Bank, Cash, Ovo" required>
				@error('method')
				<div class="invalid-feedback">{{ $message }}</div>
				@enderror
			</div>
		</div>

		<div class="card-footer text-end">
			<a href="{{ route('payment-method.index') }}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Kembali</a>
			<button type="button" id="btnSubmit" class="btn btn-success"><i class="fas fa-check"></i> Simpan Metode</button>
		</div>
	</form>
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(function () {
		// Aktifkan tooltip Bootstrap
		$('[data-bs-toggle="tooltip"]').tooltip();

		// SweetAlert konfirmasi sebelum submit form
		$('#btnSubmit').click(function (e) {
			e.preventDefault();
			Swal.fire({
				title: 'Simpan Metode Pembayaran Baru?',
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
