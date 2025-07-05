<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPackageController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiInvoiceController;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiUserProfileController;

//For WebApp
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserProfileController;

Route::get('/test-web', function () {
    return ['message' => 'WEB is working!'];
});
Route::get('/v1/packages', [ApiPackageController::class, 'index']);// For mobile app API

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

//Mobile API user profile
Route::middleware('auth:sanctum')->prefix('v1')->group(
    function () {
        Route::post('/logout', [ApiAuthController::class, 'logout']);

        // Retrieve user data
        Route::get('/users', [ApiUserProfileController::class, 'index']);
        Route::get('/users/{user}', [ApiUserProfileController::class, 'show']);
    }
);

// Packages (read-only for users)
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{package}', [PackageController::class, 'show']);

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
;
