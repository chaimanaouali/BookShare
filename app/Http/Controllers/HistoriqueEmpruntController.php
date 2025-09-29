<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueEmprunt;
use App\Models\Emprunt;
use App\Models\User;
use Illuminate\Http\Request;

class HistoriqueEmpruntController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $historiqueEmprunts = HistoriqueEmprunt::with(['emprunt', 'utilisateur'])->paginate(10);
        return view('historique-emprunts.index', compact('historiqueEmprunts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $emprunts = Emprunt::all();
        $utilisateurs = User::all();
        return view('historique-emprunts.create', compact('emprunts', 'utilisateurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'emprunt_id' => 'required|exists:emprunts,id',
            'utilisateur_id' => 'required|exists:users,id',
            'action' => 'required|string|max:255',
            'date_action' => 'required|date',
            'details' => 'nullable|string',
        ]);

        HistoriqueEmprunt::create($request->all());

        return redirect()->route('historique-emprunts.index')->with('success', 'Entrée d\'historique créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoriqueEmprunt $historiqueEmprunt)
    {
        $historiqueEmprunt->load(['emprunt', 'utilisateur']);
        return view('historique-emprunts.show', compact('historiqueEmprunt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoriqueEmprunt $historiqueEmprunt)
    {
        $emprunts = Emprunt::all();
        $utilisateurs = User::all();
        return view('historique-emprunts.edit', compact('historiqueEmprunt', 'emprunts', 'utilisateurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoriqueEmprunt $historiqueEmprunt)
    {
        $request->validate([
            'emprunt_id' => 'required|exists:emprunts,id',
            'utilisateur_id' => 'required|exists:users,id',
            'action' => 'required|string|max:255',
            'date_action' => 'required|date',
            'details' => 'nullable|string',
        ]);

        $historiqueEmprunt->update($request->all());

        return redirect()->route('historique-emprunts.index')->with('success', 'Entrée d\'historique modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriqueEmprunt $historiqueEmprunt)
    {
        $historiqueEmprunt->delete();

        return redirect()->route('historique-emprunts.index')->with('success', 'Entrée d\'historique supprimée avec succès.');
    }
}
