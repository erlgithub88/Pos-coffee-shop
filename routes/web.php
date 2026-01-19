<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemSupplyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


// ✅ Tambahkan route root supaya tidak not found saat akses via ngrok
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
        ->name('google.login');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/', [UserController::class, 'store'])->name('user.store');
        Route::get('/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    Route::prefix('item')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('item.index');
        Route::get('/create', [ItemController::class, 'create'])->name('item.create');
        Route::post('/', [ItemController::class, 'store'])->name('item.store');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('item.edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('item.update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('item.destroy');
    });

    Route::prefix('item-supply')->group(function () {
        Route::get('/', [ItemSupplyController::class, 'index'])->name('item-supply.index');
        Route::get('/create', [ItemSupplyController::class, 'create'])->name('item-supply.create');
        Route::post('/', [ItemSupplyController::class, 'store'])->name('item-supply.store');
        Route::get('/{itemSupply}', [ItemSupplyController::class, 'edit'])->name('item-supply.edit');
        Route::put('/{itemSupply}', [ItemSupplyController::class, 'update'])->name('item-supply.update');
        Route::delete('/{itemSupply}', [ItemSupplyController::class, 'destroy'])->name('item-supply.destroy');
    });

    Route::prefix('cashflow')->group(function () {
        Route::get('/', [CashflowController::class, 'index'])->name('cashflow.index');
        Route::get('/create', [CashflowController::class, 'create'])->name('cashflow.create');
        Route::post('/', [CashflowController::class, 'store'])->name('cashflow.store');
        Route::get('/{cashflow}/edit', [CashflowController::class, 'edit'])->name('cashflow.edit');
        Route::put('/{cashflow}', [CashflowController::class, 'update'])->name('cashflow.update');
        Route::delete('/{cashflow}', [CashflowController::class, 'destroy'])->name('cashflow.destroy');
    });

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::get('/create', [OrderController::class, 'create'])->name('order.create');
        Route::post('/', [OrderController::class, 'store'])->name('order.store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('order.detail');
        Route::put('/{order}', [OrderController::class, 'update'])->name('order.update');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('order.destroy');
        Route::get('/{id}/print', [OrderController::class, 'print'])->name('order.print');
        Route::get('/{order}', [OrderController::class, 'show'])->name('order.show');
        Route::get('/{order}/details', [OrderController::class, 'details'])->name('order.details');
    });

    Route::prefix('payment-method')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('payment-method.index');
        Route::get('/create', [PaymentMethodController::class, 'create'])->name('payment-method.create');
        Route::post('/', [PaymentMethodController::class, 'store'])->name('payment-method.store');
        Route::get('/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-method.edit');
        Route::put('/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-method.update');
        Route::delete('/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-method.destroy');
    });

    Route::get('/report/transaction', [ReportController::class, 'transaction'])->name('report.transaction');
    Route::get('/report/cashflow', [ReportController::class, 'cashflow'])->name('report.cashflow');
    Route::get('/report/transaction/export', [ReportController::class, 'export'])->name('report.transaction.export');
    Route::get('/report/export-pdf', [ReportController::class, 'exportPdf'])->name('report.export.pdf');
});
