<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LivresController extends Controller
{
    /**
     * Display the livres page with avis integration
     */
    public function index()
    {
        $livres = Livre::with(['avis.utilisateur'])->get();
        
        // Calculate average ratings for each livre
        foreach ($livres as $livre) {
            $livre->average_rating = $livre->avis->avg('note') ?? 0;
            $livre->total_reviews = $livre->avis->count();
        }
        
        return view('front.livres', compact('livres'));
    }

    /**
     * Get avis for a specific livre
     */
    public function getAvis(Request $request, $livreId): JsonResponse
    {
        $avis = Avis::with('utilisateur')
            ->where('livre_id', $livreId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $avis
        ]);
    }

    /**
     * Store a new avis
     */
    public function storeAvis(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'livre_id' => ['required', 'integer', 'exists:livres,id'],
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['required', 'string', 'max:1000'],
        ]);

        // For now, we'll use a default user ID (you can modify this based on your auth system)
        $validated['user_id'] = 1; // This should be the authenticated user's ID

        $avis = Avis::create($validated);
        $avis->load('utilisateur');

        return response()->json([
            'success' => true,
            'data' => $avis,
            'message' => 'Review added successfully'
        ], 201);
    }

    /**
     * Update an existing avis
     */
    public function updateAvis(Request $request, $avisId): JsonResponse
    {
        $avis = Avis::findOrFail($avisId);

        $validated = $request->validate([
            'note' => ['sometimes', 'required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['sometimes', 'required', 'string', 'max:1000'],
        ]);

        $avis->update($validated);
        $avis->load('utilisateur');

        return response()->json([
            'success' => true,
            'data' => $avis,
            'message' => 'Review updated successfully'
        ]);
    }

    /**
     * Delete an avis
     */
    public function deleteAvis($avisId): JsonResponse
    {
        $avis = Avis::findOrFail($avisId);
        $avis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }
}
