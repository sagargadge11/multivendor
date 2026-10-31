<?php
// MERGE these route definitions into your existing routes/web.php.
// Do not replace the entire web.php file.

use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;

Route::middleware(['auth', 'role:vendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/vendors', [AdminVendorController::class, 'index'])
            ->name('vendors.index');

        Route::get('/vendors/{vendor}', [AdminVendorController::class, 'show'])
            ->name('vendors.show');

        Route::post('/vendors/{vendor}/approve', [AdminVendorController::class, 'approve'])
            ->name('vendors.approve');

        Route::post('/vendors/{vendor}/reject', [AdminVendorController::class, 'reject'])
            ->name('vendors.reject');

        Route::post('/vendors/{vendor}/suspend', [AdminVendorController::class, 'suspend'])
            ->name('vendors.suspend');

        Route::post('/vendors/{vendor}/reactivate', [AdminVendorController::class, 'reactivate'])
            ->name('vendors.reactivate');
    });
