@extends('layouts.admin-layout')

@section('title', 'Edit Payment Method')

@section('content')
<div class="card shadow-sm rounded">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="fas fa-edit mr-2"></i> Edit Payment Method
        </h3>
    </div>

    <form id="editForm" action="{{ route('payment-method.update', $paymentMethod->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="form-group mb-3">
                <label for="method" data-bs-toggle="tooltip" title="Nama metode pembayaran yang akan ditampilkan.">
                    <i class="fas fa-credit-card text-secondary mr-1"></i> Method
                </label>
                <input type="text" name="method" value="{{ old('method', $paymentMethod->method) }}"
                    class="form-control @error('method') is-invalid @enderror" id="method"
                    placeholder="Contoh: Transfer Bank, Cash, e-Wallet" required>
                @error('method')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('payment-method.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" id="btnSubmit" class="btn btn-success">
                <i class="fas fa-check"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Konfirmasi SweetAlert sebelum submit
        $('#btnSubmit').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Perubahan pada payment method akan disimpan.",
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
