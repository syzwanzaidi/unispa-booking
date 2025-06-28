<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;

// Home Page
Route::get('/', function () {
    return view('index');
})->name('home');

// Unified Login/Register routes handled by AuthController
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Public Packages viewing
Route::resource('packages', PackageController::class)->only(['index', 'show']);

// This ensures the logout route is accessible to both regular users and admins
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth:web')->group(function () { // Apply 'web' guard for this group
    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Packages management (if users can manage, otherwise move to admin group)
    Route::resource('packages', PackageController::class)->except(['index', 'show']);

    // Booking management by regular users
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // User viewing their own invoices
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show'); // For single invoice view
    Route::get('invoices', [InvoiceController::class, 'show'])->name('invoices.index'); // For list of invoices

    // User managing their payments
    Route::resource('payments', PaymentController::class);
});
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin-specific routes protected by the 'admin' guard
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        Route::resource('admins', AdminController::class);
        Route::resource('packages', AdminPackageController::class)->names('packages');
        Route::resource('bookings', AdminBookingController::class)->names('bookings')->only(['index', 'show', 'edit', 'update']);
        Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::resource('invoices', AdminInvoiceController::class)->only(['index', 'show']);
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminReportController::class, 'index'])->name('index');
            Route::get('sales/daily', [AdminReportController::class, 'dailySales'])->name('sales.daily');
            Route::get('sales/monthly', [AdminReportController::class, 'monthlySales'])->name('sales.monthly');
            Route::get('sales/yearly', [AdminReportController::class, 'yearlySales'])->name('sales.yearly');
        });
    });

});
