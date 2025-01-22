<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Api\AuthController;
use App\Http\Controllers\Admin\Api\ProductController;
use Symfony\Component\HttpFoundation\Response;

// Middleware to handle unauthenticated requests
Route::middleware(['auth:sanctum'])->group(function () {
    // Protected API Routes
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Product API Routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// Public API Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Testing Route (Optional)
Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
    return $request->user();
});

// Global Exception Handler for API Middleware
