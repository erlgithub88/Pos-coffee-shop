@extends('layouts.admin-layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">

    <style>
        /* Header Dashboard Member */
        .dashboard-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 28px;
            color: #5C2A1D;
            margin-bottom: 25px;
        }
        .dashboard-header i {
            font-size: 34px;
            color: #A17A57;
            opacity: 0.85;
        }
    </style>
@endpush

@section('content')
<!-- Header Dashboard Member -->
<div class="dashboard-header">
    <i class="fas fa-user-circle"></i>
    <span>Dashboard Member</span>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Profil Singkat -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Profil Anda
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . auth()->user()->image_path) }}" alt="Foto Profil" class="img-fluid rounded-circle mb-3" style="width: 150px;">
                    <h4>{{ auth()->user()->member->name }}</h4>
                    <p>Status Keanggotaan: <strong>Aktif</strong></p>
                    <p>Email: {{ auth()->user()->email }}</p>
                    <p>Telepon: {{ auth()->user()->member->phone_number }}</p>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Transaksi</span>
                            <span class="info-box-number">{{ $totalTransactions ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Poin Reward</span>
                            <span class="info-box-number">{{ $totalPoints ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-box"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Produk Dibeli</span>
                            <span class="info-box-number">{{ $totalProductsBought ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histori Order -->
            <div class="card mt-3">
                <div class="card-header">
                    Histori Order Terakhir
                </div>
                <div class="card-body p-3 text-muted">
                    Data histori belum tersedia untuk saat ini.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/pages/dashboard.js') }}"></script>
@endpush
