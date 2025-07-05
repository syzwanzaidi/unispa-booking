<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PackageController;

Route::get('/test-web', function () {
    return ['message' => 'WEB is working!'];
});

Route::get('/packages', [PackageController::class, 'apiIndex']);
