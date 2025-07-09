<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    FinanceController,
    LandboardController,
    PaymentController,
    PenaltyController,
    RenewalController,
    RoomController,
    TenantController,
    RentalHistoryController,
    RoomTransferController,
    ForgotPasswordController,
    PasswordResetController,
    ResetPasswordController
};

Route::redirect('/', '/login')->name('auth');

Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

Route::prefix('landboard')->middleware(['auth', 'role:landboard'])->name('landboard.')->group(function () {

    Route::get('/dashboard', [LandboardController::class, 'dashboard'])->name('dashboard.index');
    Route::get('/update-profile', [LandboardController::class, 'editProfile'])->name('profile.update-form');
    Route::post('/update-profile', [LandboardController::class, 'updateProfile'])->name('profile.update');

    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/room/create', [RoomController::class, 'create'])->name('rooms.create-form');
    Route::post('/room/store', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/room/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::get('/room/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit-form');
    Route::put('/room/{id}/update', [RoomController::class, 'update'])->name('rooms.update');
    Route::get('/room/{id}/duplicate', [RoomController::class, 'duplicateForm'])->name('rooms.duplicate-form');
    Route::post('/room/{id}/duplicate', [RoomController::class, 'duplicate'])->name('rooms.duplicate');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [LandboardController::class, 'showCreateTenantForm'])->name('tenants.create-form');
    Route::post('/tenants/store', [LandboardController::class, 'storeNewTenant'])->name('tenants.store');
    Route::get('/tenants/{id}', [LandboardController::class, 'show'])->name('tenants.show');
    Route::get('/tenants/{id}/edit', [LandboardController::class, 'showEditTenantForm'])->name('tenants.edit');
    Route::put('/tenants/{id}', [LandboardController::class, 'updateTenantData'])->name('tenants.update');
    Route::delete('/tenants/{id}', [LandboardController::class, 'deactivateTenant'])->name('tenants.destroy');
    Route::get('/tenants/{id}/reactivate', [LandboardController::class, 'showReactivateForm'])->name('tenants.reactivate.form');
    Route::post('/tenants/{id}/reactivate', [LandboardController::class, 'reactivateTenant'])->name('tenants.reactivate');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/payments', [PaymentController::class, 'cashIndex'])->name('payments.index');
    Route::post('/payments/{payment}/mark-paid', [PaymentController::class, 'markAsPaid'])->name('payments.markPaid');

    Route::get('/rental-histories', [RentalHistoryController::class, 'landboardIndex'])->name('rental.history.landboard');

    Route::get('/penalty-settings', [PenaltyController::class, 'settings'])->name('penalty.settings');
    Route::post('/penalty-settings', [PenaltyController::class, 'update'])->name('penalty.update');
    
    Route::get('/room-transfer-requests', [RoomTransferController::class, 'index'])->name('room-transfer.index');
    Route::post('/room-transfer-requests/{id}/handle', [RoomTransferController::class, 'handleAction'])->name('room-transfer.handle');

});

Route::prefix('tenant')->middleware(['auth', 'role:tenant'])->name('tenant.')->group(function () {

    Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('dashboard.index');
    Route::get('/profile/complete', [TenantController::class, 'createProfile'])->name('profile.complete-form');
    Route::post('/profile/complete', [TenantController::class, 'storeProfile'])->name('profile.complete.store');
    Route::get('/profile/edit', [TenantController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [TenantController::class, 'updateProfile'])->name('profile.update');

    Route::get('/riwayat-sewa', [RentalHistoryController::class, 'index'])->name('rental.history');

    Route::get('/pembayaran/list', [PaymentController::class, 'showPaymentList'])->name('payment.list');
    Route::get('/pembayaran/riwayat', [PaymentController::class, 'history'])->name('payment.history');
    Route::get('/pembayaran/check-status', [PaymentController::class, 'checkStatus'])->name('payment.status');
    Route::post('/invoice/{rentalId}', [PaymentController::class, 'createInvoice'])->name('payment.createInvoice');
    Route::post('/invoice/{rentalId}/pay', [PaymentController::class, 'createInvoice'])->name('invoice.pay');

    Route::get('/perpanjang-sewa', [RenewalController::class, 'directForm'])->name('renewal.direct');
    Route::get('/perpanjang/{id}', [RenewalController::class, 'showForm'])->name('renewal.form');
    Route::post('/perpanjang/{id}', [RenewalController::class, 'store'])->name('renewal.store');
    Route::post('/renewal/{id}', [RenewalController::class, 'store'])->name('renewal.save');

    Route::get('/room-transfer', [RoomTransferController::class, 'showForm'])->name('room-transfer.form');
    Route::post('/room-transfer', [RoomTransferController::class, 'process'])->name('room-transfer.process');
});
