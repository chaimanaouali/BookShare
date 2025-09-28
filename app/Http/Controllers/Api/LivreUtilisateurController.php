<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LivreUtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the livre utilisateurs for a specific bibliotheque.
     * Optionally, you can pass ?bibliotheque_id= in the query or as a route param.
     */
    public function index(Request $request)
    {
        $bibliothequeId = $request->bibliothequeId ?? $request->query('bibliotheque_id');
        if (!$bibliothequeId) {
            return response()->json(['success' => false, 'message' => 'bibliotheque_id is required'], 400);
        }
        $livres = \App\Models\LivreUtilisateur::with(['livre', 'utilisateur'])
            ->where('bibliotheque_id', $bibliothequeId)
            ->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $livres,
            'message' => 'Livre utilisateurs retrieved successfully'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created livre utilisateur in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'bibliotheque_id' => ['required', 'exists:bibliotheque_virtuelles,id'],
            'livre_id' => ['required', 'exists:livres,id'],
            'fichier_livre' => ['required', 'string'],
            'format' => ['nullable', 'string'],
            'taille' => ['nullable', 'string'],
            'visibilite' => ['nullable', 'in:public,private'],
            'description' => ['nullable', 'string'],
        ]);
        $livreUtilisateur = \App\Models\LivreUtilisateur::create($validated);
        return response()->json([
            'success' => true,
            'data' => $livreUtilisateur,
            'message' => 'Livre utilisateur created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified livre utilisateur.
     */
    public function show($id)
    {
        $livreUtilisateur = \App\Models\LivreUtilisateur::with(['livre', 'utilisateur', 'bibliotheque'])->find($id);
        if (!$livreUtilisateur) {
            return response()->json(['success' => false, 'message' => 'Livre utilisateur not found'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $livreUtilisateur,
            'message' => 'Livre utilisateur retrieved successfully'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified livre utilisateur in storage.
     */
    public function update(Request $request, $id)
    {
        $livreUtilisateur = \App\Models\LivreUtilisateur::find($id);
        if (!$livreUtilisateur) {
            return response()->json(['success' => false, 'message' => 'Livre utilisateur not found'], 404);
        }
        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'bibliotheque_id' => ['sometimes', 'exists:bibliotheque_virtuelles,id'],
            'livre_id' => ['sometimes', 'exists:livres,id'],
            'fichier_livre' => ['sometimes', 'string'],
            'format' => ['nullable', 'string'],
            'taille' => ['nullable', 'string'],
            'visibilite' => ['nullable', 'in:public,private'],
            'description' => ['nullable', 'string'],
        ]);
        $livreUtilisateur->update($validated);
        return response()->json([
            'success' => true,
            'data' => $livreUtilisateur,
            'message' => 'Livre utilisateur updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified livre utilisateur from storage.
     */
    public function destroy($id)
    {
        $livreUtilisateur = \App\Models\LivreUtilisateur::find($id);
        if (!$livreUtilisateur) {
            return response()->json(['success' => false, 'message' => 'Livre utilisateur not found'], 404);
        }
        $livreUtilisateur->delete();
        return response()->json([
            'success' => true,
            'message' => 'Livre utilisateur deleted successfully'
        ]);
    }
}
