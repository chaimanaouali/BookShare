<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvisController extends Controller
{
    /**
     * Display a listing of the avis.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, (int) $request->query('per_page', 15));
        $avis = Avis::with(['utilisateur', 'livre'])->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $avis,
            'message' => 'Avis retrieved successfully'
        ]);
    }

    /**
     * Store a newly created avis in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'utilisateur_id' => ['required', 'integer', 'exists:users,id'],
            'livre_id' => ['required', 'integer', 'exists:livres,id'],
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['required', 'string', 'max:1000'],
        ]);

        $avis = Avis::create($validated);
        $avis->load(['utilisateur', 'livre']);

        return response()->json([
            'success' => true,
            'data' => $avis,
            'message' => 'Avis created successfully'
        ], 201);
    }

    /**
     * Display the specified avis.
     */
    public function show(Avis $avi): JsonResponse
    {
        $avi->load(['utilisateur', 'livre']);
        
        return response()->json([
            'success' => true,
            'data' => $avi,
            'message' => 'Avis retrieved successfully'
        ]);
    }

    /**
     * Update the specified avis in storage.
     */
    public function update(Request $request, Avis $avi): JsonResponse
    {
        $validated = $request->validate([
            'utilisateur_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'livre_id' => ['sometimes', 'required', 'integer', 'exists:livres,id'],
            'note' => ['sometimes', 'required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['sometimes', 'required', 'string', 'max:1000'],
            'date_publication' => ['sometimes', 'date'],
        ]);

        $avi->update($validated);
        $avi->load(['utilisateur', 'livre']);

        return response()->json([
            'success' => true,
            'data' => $avi,
            'message' => 'Avis updated successfully'
        ]);
    }

    /**
     * Remove the specified avis from storage.
     */
    public function destroy(Avis $avi): JsonResponse
    {
        $avi->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Avis deleted successfully'
        ]);
    }
}


