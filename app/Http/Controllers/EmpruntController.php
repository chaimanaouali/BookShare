<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use App\Http\Requests\StoreEmpruntRequest;
use Illuminate\Http\Request;

class EmpruntController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = auth()->user();
        
        // Filter emprunts to show only the authenticated user's emprunts
        $emprunts = Emprunt::with(['utilisateur', 'livre', 'historiqueEmprunts'])
            ->where('utilisateur_id', $currentUser->id)
            ->paginate(10);
        
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
    public function store(StoreEmpruntRequest $request)
    {
        $validated = $request->validated();

        $emprunt = Emprunt::create($validated);

        // Create historique entry
        $emprunt->historiqueEmprunts()->create([
            'utilisateur_id' => $validated['utilisateur_id'],
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
        // Ensure the user can only view their own emprunts
        if ($emprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cet emprunt.');
        }
        
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
        // Ensure the user can only edit their own emprunts
        if ($emprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet emprunt.');
        }
        
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
        // Ensure the user can only update their own emprunts
        if ($emprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet emprunt.');
        }
        
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
        // Ensure the user can only delete their own emprunts
        if ($emprunt->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cet emprunt.');
        }
        
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

    /**
     * Emprunter un livre directement depuis la page du livre
     */
    public function emprunterLivre(Request $request, Livre $livre)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur a déjà emprunté ce livre et qu'il est encore actif
        $empruntActif = Emprunt::where('utilisateur_id', $user->id)
            ->where('livre_id', $livre->id)
            ->where('statut', 'emprunté')
            ->first();

        if ($empruntActif) {
            return redirect()->back()->with('error', 'Vous avez déjà emprunté ce livre.');
        }

        // Vérifier si le livre est disponible
        if (!$livre->disponibilite) {
            return redirect()->back()->with('error', 'Ce livre n\'est pas disponible pour l\'emprunt.');
        }

        // Créer l'emprunt
        $emprunt = Emprunt::create([
            'utilisateur_id' => $user->id,
            'livre_id' => $livre->id,
            'date_emprunt' => now(),
            'date_retour_prev' => now()->addDays(14), // 14 jours par défaut
            'statut' => 'emprunté',
            'penalite' => 0,
            'commentaire' => 'Emprunt automatique depuis la page du livre',
        ]);

        // Créer l'entrée historique
        $emprunt->historiqueEmprunts()->create([
            'utilisateur_id' => $user->id,
            'action' => 'Emprunt automatique',
            'date_action' => now(),
            'details' => 'Livre emprunté depuis la page du livre',
        ]);

        return redirect()->route('emprunts.show', $emprunt)
            ->with('success', 'Livre emprunté avec succès ! Vous pouvez maintenant le lire.');
    }

    /**
     * Retourner un livre
     */
    public function retournerLivre(Emprunt $emprunt)
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur peut retourner ce livre
        if ($emprunt->utilisateur_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à retourner ce livre.');
        }

        if ($emprunt->statut !== 'emprunté') {
            return redirect()->back()->with('error', 'Ce livre n\'est pas en cours d\'emprunt.');
        }

        // Mettre à jour l'emprunt
        $emprunt->update([
            'statut' => 'retourné',
            'date_retour_eff' => now(),
        ]);

        // Créer l'entrée historique
        $emprunt->historiqueEmprunts()->create([
            'utilisateur_id' => $user->id,
            'action' => 'Retour',
            'date_action' => now(),
            'details' => 'Livre retourné',
        ]);

        return redirect()->route('emprunts.index')
            ->with('success', 'Livre retourné avec succès !');
    }

    /**
     * Vérifier si un utilisateur peut lire un livre (a un emprunt actif)
     */
    public function peutLireLivre(Livre $livre)
    {
        $user = auth()->user();
        
        $empruntActif = Emprunt::where('utilisateur_id', $user->id)
            ->where('livre_id', $livre->id)
            ->where('statut', 'emprunté')
            ->first();

        return $empruntActif !== null;
    }
}
