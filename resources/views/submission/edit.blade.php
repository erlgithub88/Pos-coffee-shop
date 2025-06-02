@extends('layouts.admin-layout')

@section('title', 'Edit Pengajuan')

@section('content')
<div class="card shadow-sm rounded">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0"><i class="fas fa-edit mr-2"></i> Edit Pengajuan</h3>
    </div>
    <form id="editForm" action="{{ route('submission.update', $submission->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="nama_barang" data-bs-toggle="tooltip" title="Nama barang yang diajukan.">
                    <i class="fas fa-tag text-secondary mr-1"></i> Nama Barang
                </label>
                <input type="text" name="nama_barang" id="nama_barang"
                    value="{{ old('nama_barang', $submission->nama_barang) }}"
                    class="form-control @error('nama_barang') is-invalid @enderror"
                    placeholder="Masukkan nama barang" required>
                @error('nama_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('submission.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" id="btnSubmit" class="btn btn-primary">
                <i class="fas fa-save"></i> Submit
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btnSubmit').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Data pengajuan akan diperbarui.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editForm').submit();
            }
        });
    });
</script>
@endpush
