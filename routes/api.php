<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

// Authentication Routes
Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    // User Management API Routes with custom endpoints
    Route::get('/users/all', [UserController::class, 'index']);
    Route::post('/users/create', [UserController::class, 'store']);
    Route::get('/users/get/{user}', [UserController::class, 'show']);
    Route::put('/users/update/{user}', [UserController::class, 'update']);
    Route::delete('/users/delete/{user}', [UserController::class, 'destroy']);
});
