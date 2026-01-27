<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ReservationController as CustomerReservationController;
use App\Http\Controllers\Customer\FeedbackController as CustomerFeedbackController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SaungController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Landing Page)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/vouchers', [LandingController::class, 'vouchers'])->name('vouchers');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
// Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/send-otp', [RegisterController::class, 'sendOTP'])->name('register.send-otp');
Route::post('/register/verify-otp', [RegisterController::class, 'verifyOTP'])->name('register.verify-otp');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOTP'])->name('forgot-password.send-otp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOTP'])->name('forgot-password.verify-otp');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->name('customer.')->middleware(['auth', 'role:customer'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Reservation (Saung Nyonyah)
    Route::get('/reservations', [CustomerReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [CustomerReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [CustomerReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{id}', [CustomerReservationController::class, 'show'])->name('reservations.show');
    
    // AJAX endpoints for reservations
    Route::post('/reservations/available-time-slots', [CustomerReservationController::class, 'getAvailableTimeSlots'])->name('reservations.available-time-slots');
    Route::post('/reservations/available-saungs', [CustomerReservationController::class, 'getAvailableSaungs'])->name('reservations.available-saungs');
    Route::post('/reservations/check-voucher', [CustomerReservationController::class, 'checkVoucher'])->name('reservations.check-voucher');
    
    // Deposit for reservations
    Route::post('/reservations/{id}/upload-deposit', [CustomerReservationController::class, 'uploadDepositProof'])->name('reservations.upload-deposit');
    
    // Feedback for reservations
    Route::get('/reservations/{reservation}/feedback', [CustomerFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/reservations/{reservation}/feedback', [CustomerFeedbackController::class, 'store'])->name('feedback.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,owner'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Only Admin (not Owner)
    Route::middleware('role:admin')->group(function () {
        // Reservations (Saung Nyonyah)
        Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::get('reservations/create', [AdminReservationController::class, 'create'])->name('reservations.create');
        Route::post('reservations', [AdminReservationController::class, 'store'])->name('reservations.store');
        Route::get('reservations/{id}', [AdminReservationController::class, 'show'])->name('reservations.show');
        Route::post('reservations/{id}/cancel', [AdminReservationController::class, 'cancel'])->name('reservations.cancel');
        Route::post('reservations/{id}/complete', [AdminReservationController::class, 'complete'])->name('reservations.complete');
        Route::post('reservations/{id}/update-notes', [AdminReservationController::class, 'updateNotes'])->name('reservations.update-notes');
        Route::post('reservations/{id}/update-status', [AdminReservationController::class, 'updateStatus'])->name('reservations.update-status');

        // Menus (Saung Nyonyah)
        Route::resource('menus', MenuController::class);
        Route::post('menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.toggle-status');

        // Saungs (Saung Nyonyah)
        Route::resource('saungs', SaungController::class);
        Route::post('saungs/{saung}/toggle-status', [SaungController::class, 'toggleStatus'])->name('saungs.toggle-status');
        
        // Saung Schedules
        Route::get('saungs/{saung}/schedules', [SaungController::class, 'schedules'])->name('saungs.schedules');
        Route::post('saungs/{saung}/schedules', [SaungController::class, 'storeSchedule'])->name('saungs.schedules.store');
        Route::delete('saungs/{saung}/schedules/{schedule}', [SaungController::class, 'deleteSchedule'])->name('saungs.schedules.delete');
        Route::post('saungs/{saung}/schedules/{schedule}/toggle', [SaungController::class, 'toggleScheduleStatus'])->name('saungs.schedules.toggle');

        // Deposits
        Route::get('deposits', [DepositController::class, 'index'])->name('deposits.index');
        Route::get('deposits/{deposit}', [DepositController::class, 'show'])->name('deposits.show');
        Route::post('deposits/{deposit}/approve', [DepositController::class, 'approve'])->name('deposits.approve');
        Route::post('deposits/{deposit}/reject', [DepositController::class, 'reject'])->name('deposits.reject');

        // Vouchers
        Route::resource('vouchers', VoucherController::class);
        Route::post('vouchers/{voucher}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle-status');
        Route::get('vouchers/{voucher}/usage', [VoucherController::class, 'usage'])->name('vouchers.usage');

        // Members
        Route::get('members', [MemberController::class, 'index'])->name('members.index');
        Route::get('members/{member}', [MemberController::class, 'show'])->name('members.show');
        Route::post('members/{member}/activate', [MemberController::class, 'activateMember'])->name('members.activate');
        Route::post('members/{member}/deactivate', [MemberController::class, 'deactivateMember'])->name('members.deactivate');
        Route::post('members/{member}/update-discount', [MemberController::class, 'updateDiscount'])->name('members.update-discount');

        // Feedbacks
        Route::get('feedbacks', [AdminFeedbackController::class, 'index'])->name('feedbacks.index');
        Route::get('feedbacks/{feedback}', [AdminFeedbackController::class, 'show'])->name('feedbacks.show');
        Route::post('feedbacks/{feedback}/toggle-visibility', [AdminFeedbackController::class, 'toggleVisibility'])->name('feedbacks.toggle-visibility');
        Route::delete('feedbacks/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedbacks.destroy');

        // Settings (WhatsApp Configuration)
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/test-connection', [SettingController::class, 'testConnection'])->name('settings.test-connection');
    });
});
