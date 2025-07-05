<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPackageController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiInvoiceController;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiUserProfileApiController;

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Profile API Routes
    Route::get('/profile', [ApiUserProfileApiController::class, 'show']); // Get current user's profile
    Route::put('/profile', [ApiUserProfileApiController::class, 'update']); // Update current user's profile
    Route::put('/profile/password', [ApiUserProfileApiController::class, 'updatePassword']); // Update current user's password

    // Packages (read-only for users)
    Route::get('/packages', [ApiPackageController::class, 'index']);
    Route::get('/packages/{package}', [ApiPackageController::class, 'show']);

    // Bookings (CRUD for authenticated users)
    Route::get('/bookings', [ApiBookingController::class, 'index']);
    Route::post('/bookings', [ApiBookingController::class, 'store']);
    Route::get('/bookings/{booking}', [ApiBookingController::class, 'show']);
    Route::put('/bookings/{booking}', [ApiBookingController::class, 'update']);
    Route::patch('/bookings/{booking}/cancel', [ApiBookingController::class, 'cancel']); // Custom route for cancelling
    Route::delete('/bookings/{booking}', [ApiBookingController::class, 'destroy']);

    // Invoices (read-only for users)
    Route::get('/invoices', [ApiInvoiceController::class, 'index']); // List all invoices for the user
    Route::get('/invoices/{invoice}', [ApiInvoiceController::class, 'show']); // Show a specific invoice

    // Payments (for users to make/view their payments)
    Route::get('/payments', [ApiPaymentController::class, 'index']);
    Route::post('/payments', [ApiPaymentController::class, 'store']); // To process a payment
    Route::get('/payments/{payment}', [ApiPaymentController::class, 'show']);
});
