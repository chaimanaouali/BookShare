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
        $currentUser = auth()->user();
        
        // Filter historique emprunts to show only the authenticated user's history
        $historiqueEmprunts = HistoriqueEmprunt::with(['emprunt.livre', 'utilisateur'])
            ->where('utilisateur_id', $currentUser->id)
            ->orderBy('date_action', 'desc')
            ->paginate(10);
        
        // Check if accessed from front-end (not from dashboard)
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return view('front.historique-emprunts.index', compact('historiqueEmprunts'));
        }
            
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
        // Ensure the user can only view their own historique emprunts
        if ($historiqueEmprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cet historique.');
        }
        
        $historiqueEmprunt->load(['emprunt.livre', 'utilisateur']);
        
        // Check if accessed from front-end (not from dashboard)
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return view('front.historique-emprunts.show', compact('historiqueEmprunt'));
        }
        
        return view('historique-emprunts.show', compact('historiqueEmprunt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoriqueEmprunt $historiqueEmprunt)
    {
        // Ensure the user can only edit their own historique emprunts
        if ($historiqueEmprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet historique.');
        }
        
        $emprunts = Emprunt::all();
        $utilisateurs = User::all();
        
        // Check if accessed from front-end (not from dashboard)
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            $historiqueEmprunt->load(['emprunt.livre']);
            return view('front.historique-emprunts.edit', compact('historiqueEmprunt', 'emprunts', 'utilisateurs'));
        }
        
        return view('historique-emprunts.edit', compact('historiqueEmprunt', 'emprunts', 'utilisateurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoriqueEmprunt $historiqueEmprunt)
    {
        // Ensure the user can only update their own historique emprunts
        if ($historiqueEmprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet historique.');
        }
        
        $request->validate([
            'emprunt_id' => 'required|exists:emprunts,id',
            'utilisateur_id' => 'required|exists:users,id',
            'action' => 'required|string|max:255',
            'date_action' => 'required|date',
            'details' => 'nullable|string',
        ]);

        $historiqueEmprunt->update($request->all());

        // Check if accessed from front-end and redirect accordingly
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return redirect()->route('historique-emprunts.index')->with('success', 'Entrée d\'historique modifiée avec succès.');
        }

        return redirect()->route('historique-emprunts.index')->with('success', 'Entrée d\'historique modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriqueEmprunt $historiqueEmprunt)
    {
        // Ensure the user can only delete their own historique emprunts
        if ($historiqueEmprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cet historique.');
        }
        
        $historiqueEmprunt->delete();

        // Check if accessed from front-end and redirect accordingly
        if (!request()->is('dashboard/*') && !request()->is('dashboard')) {
            return redirect()->route('historique-emprunts.index')->with('success', 'Entrée d\'historique supprimée avec succès.');
        }

        return redirect()->route('historique-emprunts.index')->with('success', 'Entrée d\'historique supprimée avec succès.');
    }
}
