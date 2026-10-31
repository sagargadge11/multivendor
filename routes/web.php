<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{vendor}', [AdminVendorController::class, 'show'])->name('vendors.show');
    Route::post('/vendors/{vendor}/approve', [AdminVendorController::class, 'approve'])->name('vendors.approve');
    Route::post('/vendors/{vendor}/reject', [AdminVendorController::class, 'reject'])->name('vendors.reject');
    Route::post('/vendors/{vendor}/suspend', [AdminVendorController::class, 'suspend'])->name('vendors.suspend');
    Route::post('/vendors/{vendor}/reactivate', [AdminVendorController::class, 'reactivate'])->name('vendors.reactivate');
});

Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});



Route::get('/run-marketplace-migrations/{key}', function ($key) {
    abort_unless(
        hash_equals((string) env('MIGRATION_SECRET'), (string) $key)
        && env('MIGRATION_SECRET'),
        404
    );

    Artisan::call('migrate', [
        '--force' => true,
    ]);

    return response()->json([
        'status' => 'success',
        'output' => Artisan::output(),
    ]);
});