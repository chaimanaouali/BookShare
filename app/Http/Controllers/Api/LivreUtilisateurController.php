<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Livre;

class LivreUtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the livres for a specific bibliotheque.
     * Optionally, you can pass ?bibliotheque_id= in the query or as a route param.
     */
    public function index(Request $request)
    {
        $bibliothequeId = $request->bibliothequeId ?? $request->query('bibliotheque_id');
        if (!$bibliothequeId) {
            return response()->json(['success' => false, 'message' => 'bibliotheque_id is required'], 400);
        }
        $livres = Livre::with(['user', 'bibliotheque'])
            ->where('bibliotheque_id', $bibliothequeId)
            ->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $livres,
            'message' => 'Livres retrieved successfully'
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
     * Store a newly created livre in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'author' => ['required', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'bibliotheque_id' => ['required', 'exists:bibliotheque_virtuelles,id'],
            'fichier_livre' => ['required', 'string'],
            'format' => ['nullable', 'string'],
            'taille' => ['nullable', 'string'],
            'visibilite' => ['nullable', 'in:public,private'],
            'user_description' => ['nullable', 'string'],
            'isbn' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string'],
            'publication_date' => ['nullable', 'date'],
            'genre' => ['nullable', 'string'],
            'langue' => ['nullable', 'string'],
            'nb_pages' => ['nullable', 'integer'],
            'resume' => ['nullable', 'string'],
            'disponibilite' => ['nullable', 'boolean'],
            'etat' => ['nullable', 'string'],
        ]);
        $livre = Livre::create($validated);
        return response()->json([
            'success' => true,
            'data' => $livre,
            'message' => 'Livre created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified livre.
     */
    public function show($id)
    {
        $livre = Livre::with(['user', 'bibliotheque'])->find($id);
        if (!$livre) {
            return response()->json(['success' => false, 'message' => 'Livre not found'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $livre,
            'message' => 'Livre retrieved successfully'
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
     * Update the specified livre in storage.
     */
    public function update(Request $request, $id)
    {
        $livre = Livre::find($id);
        if (!$livre) {
            return response()->json(['success' => false, 'message' => 'Livre not found'], 404);
        }
        $validated = $request->validate([
            'title' => ['sometimes', 'string'],
            'author' => ['sometimes', 'string'],
            'user_id' => ['sometimes', 'exists:users,id'],
            'bibliotheque_id' => ['sometimes', 'exists:bibliotheque_virtuelles,id'],
            'fichier_livre' => ['sometimes', 'string'],
            'format' => ['nullable', 'string'],
            'taille' => ['nullable', 'string'],
            'visibilite' => ['nullable', 'in:public,private'],
            'user_description' => ['nullable', 'string'],
            'isbn' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string'],
            'publication_date' => ['nullable', 'date'],
            'genre' => ['nullable', 'string'],
            'langue' => ['nullable', 'string'],
            'nb_pages' => ['nullable', 'integer'],
            'resume' => ['nullable', 'string'],
            'disponibilite' => ['nullable', 'boolean'],
            'etat' => ['nullable', 'string'],
        ]);
        $livre->update($validated);
        return response()->json([
            'success' => true,
            'data' => $livre,
            'message' => 'Livre updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified livre from storage.
     */
    public function destroy($id)
    {
        $livre = Livre::find($id);
        if (!$livre) {
            return response()->json(['success' => false, 'message' => 'Livre not found'], 404);
        }
        $livre->delete();
        return response()->json([
            'success' => true,
            'message' => 'Livre deleted successfully'
        ]);
    }
}
