<?php

namespace App\Http\Controllers;

use App\Models\Defi;
use Illuminate\Http\Request;

class DefiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $defis = Defi::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.defis.index', compact('defis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.defis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'nombre_livres_requis' => 'required|integer|min:1',
            'type_defi' => 'required|string|in:lecture,quiz,autre',
            'actif' => 'boolean'
        ]);

        Defi::create($request->all());

        return redirect()->route('defis.index')->with('success', 'Défi créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Defi $defi)
    {
        $defi->load('participations.user');
        return view('admin.defis.show', compact('defi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Defi $defi)
    {
        return view('admin.defis.edit', compact('defi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Defi $defi)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'nombre_livres_requis' => 'required|integer|min:1',
            'type_defi' => 'required|string|in:lecture,quiz,autre',
            'actif' => 'boolean'
        ]);

        $defi->update($request->all());

        return redirect()->route('defis.index')->with('success', 'Défi mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Defi $defi)
    {
        $defi->delete();
        return redirect()->route('defis.index')->with('success', 'Défi supprimé avec succès.');
    }
}
