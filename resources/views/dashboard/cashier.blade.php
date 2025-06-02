@extends('layouts.admin-layout')
@push('styles')
<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-header {
        font-family: 'Poppins', sans-serif;
        color: #5C2A1D;
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .dashboard-header i {
        font-size: 34px;
        color: #A17A57;
        opacity: 0.85;
        margin-right: 10px;
    }

    .dashboard-header h2 {
        font-weight: 600;
        font-size: 28px;
        margin: 0;
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

<div class="dashboard-header">
    <i class="fas fa-cash-register"></i>
    <h2>Dashboard Cashier</h2>
</div>

<div class="row dashboard-row">
    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>{{ $totalOrdersToday }}</h3>
                <p>Total Orders Today</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-4 d-flex justify-content-between align-items-center">
            <div class="inner">
                <h3>Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
                <p>Total Revenue Today</p>
            </div>
            <div class="icon">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>
</div>

@endsection
