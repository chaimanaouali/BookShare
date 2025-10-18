<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Http\Request;

class EmpruntController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emprunts = Emprunt::with(['utilisateur', 'livre', 'historiqueEmprunts'])->paginate(10);
        $currentUser = auth()->user();
        
        // Check if accessed from front-end (not from dashboard)
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return view('front.emprunts.index', compact('emprunts', 'currentUser'));
        }
        
        return view('emprunts.index', compact('emprunts', 'currentUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $utilisateurs = User::all();
        $livres = Livre::all();
        $currentUser = auth()->user();
        
        // Check if accessed from front-end (not from dashboard)
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return view('front.emprunts.create', compact('utilisateurs', 'livres', 'currentUser'));
        }
        
        return view('emprunts.create', compact('utilisateurs', 'livres', 'currentUser'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:users,id',
            'livre_id' => 'required|exists:livres,id',
            'date_emprunt' => 'required|date',
            'date_retour_prev' => 'required|date|after:date_emprunt',
            'statut' => 'required|string|max:255',
            'penalite' => 'nullable|numeric|min:0',
            'commentaire' => 'nullable|string',
        ]);

        $emprunt = Emprunt::create($request->all());

        // Create historique entry
        $emprunt->historiqueEmprunts()->create([
            'utilisateur_id' => $request->utilisateur_id,
            'action' => 'Création',
            'date_action' => now(),
            'details' => 'Emprunt créé',
        ]);

        // Check if accessed from front-end and redirect accordingly
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return redirect()->route('emprunts.index')->with('success', 'Emprunt créé avec succès.');
        }

        return redirect()->route('emprunts.index')->with('success', 'Emprunt créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Emprunt $emprunt)
    {
        $emprunt->load(['utilisateur', 'livre', 'historiqueEmprunts.utilisateur']);
        
        // Check if accessed from front-end (not from dashboard)
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return view('front.emprunts.show', compact('emprunt'));
        }
        
        return view('emprunts.show', compact('emprunt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emprunt $emprunt)
    {
        $utilisateurs = User::all();
        $livres = Livre::all();
        
        // Check if accessed from front-end (not from dashboard)
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return view('front.emprunts.edit', compact('emprunt', 'utilisateurs', 'livres'));
        }
        
        return view('emprunts.edit', compact('emprunt', 'utilisateurs', 'livres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emprunt $emprunt)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:users,id',
            'livre_id' => 'required|exists:livres,id',
            'date_emprunt' => 'required|date',
            'date_retour_prev' => 'required|date|after:date_emprunt',
            'date_retour_eff' => 'nullable|date|after:date_emprunt',
            'statut' => 'required|string|max:255',
            'penalite' => 'nullable|numeric|min:0',
            'commentaire' => 'nullable|string',
        ]);

        $emprunt->update($request->all());

        // Create historique entry
        $emprunt->historiqueEmprunts()->create([
            'utilisateur_id' => $request->utilisateur_id,
            'action' => 'Modification',
            'date_action' => now(),
            'details' => 'Emprunt modifié',
        ]);

        // Check if accessed from front-end and redirect accordingly
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return redirect()->route('emprunts.index')->with('success', 'Emprunt modifié avec succès.');
        }

        return redirect()->route('emprunts.index')->with('success', 'Emprunt modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emprunt $emprunt)
    {
        // Create historique entry before deletion
        $emprunt->historiqueEmprunts()->create([
            'utilisateur_id' => $emprunt->utilisateur_id,
            'action' => 'Suppression',
            'date_action' => now(),
            'details' => 'Emprunt supprimé',
        ]);

        $emprunt->delete();

        // Check if accessed from front-end and redirect accordingly
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return redirect()->route('emprunts.index')->with('success', 'Emprunt supprimé avec succès.');
        }

        return redirect()->route('emprunts.index')->with('success', 'Emprunt supprimé avec succès.');
    }
}
