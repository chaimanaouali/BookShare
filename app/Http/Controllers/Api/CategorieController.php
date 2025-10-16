<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategorieController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, (int) $request->query('per_page', 15));
        $categories = Categorie::withBookCount()
            ->orderBy('nom')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
        ]);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $categorie = Categorie::create($validated);
        $categorie->loadCount('livres');

        return response()->json([
            'success' => true,
            'data' => $categorie,
            'message' => 'Category created successfully'
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(Categorie $categorie): JsonResponse
    {
        $categorie->loadCount('livres');
        
        return response()->json([
            'success' => true,
            'data' => $categorie,
            'message' => 'Category retrieved successfully'
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Categorie $categorie): JsonResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom,' . $categorie->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $categorie->update($validated);
        $categorie->loadCount('livres');

        return response()->json([
            'success' => true,
            'data' => $categorie,
            'message' => 'Category updated successfully'
        ]);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Categorie $categorie): JsonResponse
    {
        // Check if category has books
        if ($categorie->livres()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that contains books. Please move or delete the books first.'
            ], 422);
        }

        $categorie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Get books in a specific category.
     */
    public function books(Categorie $categorie, Request $request): JsonResponse
    {
        $perPage = max(1, (int) $request->query('per_page', 15));
        $books = $categorie->livres()
            ->with(['user', 'bibliotheque'])
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $books,
            'message' => 'Books in category retrieved successfully'
        ]);
    }

    /**
     * Get popular categories (with most books).
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = max(1, (int) $request->query('limit', 10));
        $categories = Categorie::popular()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Popular categories retrieved successfully'
        ]);
    }
}
