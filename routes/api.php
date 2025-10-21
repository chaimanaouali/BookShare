<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BibliothequeVirtuelleController;
use App\Http\Controllers\Api\LivreUtilisateurController;
use App\Http\Controllers\Api\AvisController;
use App\Http\Controllers\Api\CategorieController;

// Simple embedding test route (no auth required)
Route::get('/embedding-test', function () {
    try {
        $svc = new \App\Services\GroqEmbeddingService();
        $result = $svc->generateEmbedding('Hello world, this is a test for embeddings.');
        return response()->json([
            'ok' => true,
            'dimension' => $result['dimension'],
            'first10' => array_slice($result['vector'], 0, 10),
        ]);
    } catch (\Throwable $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
    }
});

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

    // Avis Management API Routes
    Route::get('/avis/all', [AvisController::class, 'index']);
    Route::post('/avis/create', [AvisController::class, 'store']);
    Route::get('/avis/get/{avi}', [AvisController::class, 'show']);
    Route::put('/avis/update/{avi}', [AvisController::class, 'update']);
    Route::delete('/avis/delete/{avi}', [AvisController::class, 'destroy']);

    // Categories Management API Routes
    Route::get('/categories/all', [CategorieController::class, 'index']);
    Route::post('/categories/create', [CategorieController::class, 'store']);
    Route::get('/categories/get/{categorie}', [CategorieController::class, 'show']);
    Route::put('/categories/update/{categorie}', [CategorieController::class, 'update']);
    Route::delete('/categories/delete/{categorie}', [CategorieController::class, 'destroy']);
    Route::get('/categories/{categorie}/books', [CategorieController::class, 'books']);
    Route::get('/categories/popular', [CategorieController::class, 'popular']);
});
