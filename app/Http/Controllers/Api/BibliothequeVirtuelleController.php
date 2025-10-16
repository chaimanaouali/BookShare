<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BibliothequeVirtuelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BibliothequeVirtuelleController extends Controller
{
    /**
     * Display a listing of the contributor's libraries.
     */
    public function index()
    {
        $bibliotheques = Auth::user()->bibliotheques()
            ->withCount('livreUtilisateurs')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bibliotheques,
            'message' => 'Bibliotheques retrieved successfully'
        ]);
    }

    /**
     * Store a newly created bibliotheque.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_bibliotheque' => ['required', 'string', 'max:255', 'unique:bibliotheque_virtuelles,nom_bibliotheque,NULL,id,user_id,' . Auth::id()],
        ]);

        $bibliotheque = Auth::user()->bibliotheques()->create([
            'nom_bibliotheque' => $request->nom_bibliotheque,
            'nb_livres' => 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => $bibliotheque,
            'message' => 'Bibliotheque created successfully'
        ], 201);
    }

    /**
     * Display the specified bibliotheque.
     */
    public function show(string $id)
    {
        $bibliotheque = Auth::user()->bibliotheques()
            ->withCount('livreUtilisateurs')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $bibliotheque,
            'message' => 'Bibliotheque retrieved successfully'
        ]);
    }

    /**
     * Update the specified bibliotheque.
     */
    public function update(Request $request, string $id)
    {
        $bibliotheque = Auth::user()->bibliotheques()->findOrFail($id);

        $request->validate([
            'nom_bibliotheque' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('bibliotheque_virtuelles', 'nom_bibliotheque')
                    ->where('user_id', Auth::id())
                    ->ignore($bibliotheque->id)
            ],
        ]);

        $bibliotheque->update([
            'nom_bibliotheque' => $request->nom_bibliotheque,
        ]);

        return response()->json([
            'success' => true,
            'data' => $bibliotheque,
            'message' => 'Bibliotheque updated successfully'
        ]);
    }

    /**
     * Remove the specified bibliotheque.
     */
    public function destroy(string $id)
    {
        $bibliotheque = Auth::user()->bibliotheques()->findOrFail($id);
        
        // Delete all associated livre utilisateurs first
        $bibliotheque->livres()->delete();
        
        // Delete the bibliotheque
        $bibliotheque->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bibliotheque deleted successfully'
        ]);
    }
}
