<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPackageController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiInvoiceController;
use App\Http\Controllers\Api\ApiPaymentController;
use App\Http\Controllers\Api\ApiUserProfileController;
use App\Http\Controllers\Api\AdminAuthController;

//For WebApp
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserProfileController;

Route::get('/test-web', function () {
    return ['message' => 'WEB is working!'];
});

Route::get('/v1/packages', [ApiPackageController::class, 'index']);// For mobile app API
Route::delete('/v1/packages/{id}', [ApiPackageController::class, 'destroy']);
Route::post('/v1/packages', [ApiPackageController::class, 'store']);
Route::put('/v1/packages/{id}', [ApiPackageController::class, 'update']);

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

//Mobile API user profile
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/me', [ApiUserProfileController::class, 'show']);
    Route::put('/me', [ApiUserProfileController::class, 'update']);
    Route::post('/me/password', [ApiUserProfileController::class, 'updatePassword']);
});

//Mobile API Booking
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [ApiBookingController::class, 'index']);
    Route::post('/bookings', [ApiBookingController::class, 'store']);
    Route::get('/bookings/{booking}', [ApiBookingController::class, 'show']);
    Route::put('/bookings/{booking}', [ApiBookingController::class, 'update']);
    Route::patch('/bookings/{booking}/cancel', [ApiBookingController::class, 'cancel']);
    Route::delete('/bookings/{booking}', [ApiBookingController::class, 'destroy']);
});

// Packages (read-only for users)
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{package}', [PackageController::class, 'show']);

// // Bookings (CRUD for authenticated users)
// Route::get('/bookings', [ApiBookingController::class, 'index']);
// Route::post('/bookings', [ApiBookingController::class, 'store']);
// Route::get('/bookings/{booking}', [ApiBookingController::class, 'show']);
// Route::put('/bookings/{booking}', [ApiBookingController::class, 'update']);
// Route::patch('/bookings/{booking}/cancel', [ApiBookingController::class, 'cancel']); // Custom route for cancelling
// Route::delete('/bookings/{booking}', [ApiBookingController::class, 'destroy']);

// Invoices (read-only for users)
Route::get('/invoices', [ApiInvoiceController::class, 'index']); // List all invoices for the user
Route::get('/invoices/{invoice}', [ApiInvoiceController::class, 'show']); // Show a specific invoice

// Payments (for users to make/view their payments)
Route::get('/payments', [ApiPaymentController::class, 'index']);
Route::post('/payments', [ApiPaymentController::class, 'store']); // To process a payment
Route::get('/payments/{payment}', [ApiPaymentController::class, 'show']);
;


//TEST PURPOSES
Route::middleware('auth:sanctum')->get('/test-user', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
        'token' => $request->bearerToken(),
    ]);
});

Route::post('/admin/login', [AdminAuthController::class, 'login']);
