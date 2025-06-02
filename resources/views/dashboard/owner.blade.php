@extends('layouts.admin-layout')

@push('styles')
<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Header Dashboard */
    .dashboard-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        color: #5C2A1D;
        font-weight: 700;
        font-size: 28px;
    }

    .dashboard-header i {
        font-size: 32px;
        color: #A17A57;
    }

    .dashboard-card {
        border-radius: 16px;
        background-color: #FAF3EB;
        color: #5C2A1D;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
    }

    .dashboard-card .inner h3 {
        font-weight: 700;
        font-size: 28px;
        margin: 0;
    }

    .dashboard-card .inner p {
        font-size: 14px;
        margin-top: 5px;
        color: #8B5E3C;
    }

    .dashboard-card .icon {
        font-size: 40px;
        color: #A17A57;
        opacity: 0.8;
    }

    .dashboard-row .col-lg-4 {
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<!-- Header Dashboard -->
<div class="dashboard-header">
    <i class="fas fa-tachometer-alt"></i>
    <span>Dashboard Owner</span>
</div>

<div class="row dashboard-row">
    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>{{ $totalOrdersThisMonth }}</h3>
                <p>Total Orders This Month</p>
            </div>
            <div class="icon">
                <i class="fas fa-mug-hot"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>{{ $totalItemsSoldThisMonth }}</h3>
                <p>Items Sold This Month</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>Rp {{ number_format($totalRevenueThisMonth, 0, ',', '.') }}</h3>
                <p>Revenue This Month</p>
            </div>
            <div class="icon">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>Rp {{ number_format($totalExpensesThisMonth, 0, ',', '.') }}</h3>
                <p>Expenses This Month</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>Rp {{ number_format($cashflowRevenueThisMonth, 0, ',', '.') }}</h3>
                <p>Cashflow Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-up"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>Rp {{ number_format($cashflowExpensesThisMonth, 0, ',', '.') }}</h3>
                <p>Cashflow Expenses</p>
            </div>
            <div class="icon">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- ChartJS -->
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/pages/dashboard.js') }}"></script>
<script>
    const socket = io('http://localhost:9000');
    socket.on('connect', () => {
        console.log('Connected to WebSocket server');
        socket.emit('userConnected', '{{ Auth::id() }}');
    });
</script>
@endpush
