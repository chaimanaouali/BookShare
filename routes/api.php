<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BibliothequeVirtuelleController;
use App\Http\Controllers\Api\LivreUtilisateurController;

// (Removed API login/signup routes to avoid conflict with web login)

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
    
    // Bibliotheque Virtuelle Management (Contributor CRUD)
    Route::apiResource('bibliotheques', BibliothequeVirtuelleController::class);
    
    // Livre Utilisateur Management (Book instances in bibliotheques)
    Route::get('/bibliotheques/{bibliothequeId}/livres', [LivreUtilisateurController::class, 'index']);
    Route::post('/livres', [LivreUtilisateurController::class, 'store']);
    Route::get('/livres/{id}', [LivreUtilisateurController::class, 'show']);
    Route::put('/livres/{id}', [LivreUtilisateurController::class, 'update']);
    Route::delete('/livres/{id}', [LivreUtilisateurController::class, 'destroy']);
});
