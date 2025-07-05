<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserProfileApiController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Profile API Routes
    Route::get('/profile', [UserProfileApiController::class, 'show']); // Get current user's profile
    Route::put('/profile', [UserProfileApiController::class, 'update']); // Update current user's profile
    Route::put('/profile/password', [UserProfileApiController::class, 'updatePassword']); // Update current user's password

    // Packages (read-only for users)
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{package}', [PackageController::class, 'show']);

    // Bookings (CRUD for authenticated users)
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']); // Custom route for cancelling
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);

    // Invoices (read-only for users)
    Route::get('/invoices', [InvoiceController::class, 'index']); // List all invoices for the user
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']); // Show a specific invoice

    // Payments (for users to make/view their payments)
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']); // To process a payment
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
});
