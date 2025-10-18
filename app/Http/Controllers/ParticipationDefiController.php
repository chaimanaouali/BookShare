<?php

namespace App\Http\Controllers;

use App\Models\ParticipationDefi;
use App\Models\Defi;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipationDefiController extends Controller
{
    /**
     * Show the form to participate in a défi
     */
    public function create(Defi $defi)
    {
        $defi->load('livres');
        return view('participation-defis.create', compact('defi'));
    }

    /**
     * Store a new participation
     */
    public function store(Request $request, Defi $defi)
    {
        $request->validate([
            'livre_id' => 'required|exists:livres,id',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        // Vérifier que le livre est associé au défi
        if (!$defi->livres->contains($request->livre_id)) {
            return redirect()->back()
                ->withErrors(['livre_id' => 'Ce livre n\'est pas associé à ce défi.']);
        }

        // Vérifier que l'utilisateur n'a pas déjà participé à ce défi
        $existingParticipation = ParticipationDefi::where('user_id', Auth::id())
            ->where('defi_id', $defi->id)
            ->first();

        if ($existingParticipation) {
            if ($existingParticipation->status === 'termine') {
                return redirect()->back()
                    ->withErrors(['livre_id' => 'Vous avez déjà terminé ce défi. Vous ne pouvez pas participer à nouveau.']);
            } else {
                return redirect()->back()
                    ->withErrors(['livre_id' => 'Vous participez déjà à ce défi.']);
            }
        }

        // Créer la participation
        $participation = ParticipationDefi::create([
            'user_id' => Auth::id(),
            'defi_id' => $defi->id,
            'livre_id' => $request->livre_id,
            'commentaire' => $request->commentaire,
            'date_debut_lecture' => now(),
        ]);

        // Si c'est une requête AJAX, retourner JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vous participez maintenant au défi ! Bonne lecture !',
                'participation' => $participation->load(['defi', 'livre'])
            ]);
        }

        return redirect()->route('front.events.index')
            ->with('success', 'Vous participez maintenant au défi ! Bonne lecture !');
    }

    /**
     * Show user's participations
     */
    public function myParticipations()
    {
        $participations = ParticipationDefi::with(['defi', 'livre'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        // Check if this is a front-end request
        if (request()->is('my-participations')) {
            return view('front.participations.my', compact('participations'));
        }

        return view('participation-defis.my-participations', compact('participations'));
    }

    /**
     * Show participation details
     */
    public function show(ParticipationDefi $participation)
    {
        // Vérifier que l'utilisateur peut voir cette participation
        if ($participation->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $participation->load(['defi', 'livre', 'user']);
        return view('participation-defis.show', compact('participation'));
    }

    /**
     * Get participation content for modal display.
     */
    public function modalContent(ParticipationDefi $participation)
    {
        // Vérifier que l'utilisateur peut voir cette participation
        if ($participation->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $participation->load(['defi', 'livre', 'user']);
        return view('participation-defis.modal-content', compact('participation'));
    }

    /**
     * Update participation status
     */
    public function updateStatus(Request $request, ParticipationDefi $participation)
    {
        // Vérifier que l'utilisateur peut modifier cette participation
        if ($participation->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:en_cours,termine,abandonne',
            'note' => 'nullable|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $data = $request->only(['status', 'note', 'commentaire']);

        // Si le statut passe à "terminé", enregistrer la date de fin
        if ($request->status === 'termine' && $participation->status !== 'termine') {
            $data['date_fin_lecture'] = now();
        }

        $participation->update($data);

        // Calculer les scores de classement si le défi est terminé
        if ($request->status === 'termine') {
            $participation->calculateRankingScores();
        }

        // Si le défi est terminé, rediriger vers la page des événements
        if ($request->status === 'termine') {
            return redirect()->route('front.events.index')
                ->with('success', 'Félicitations ! Vous avez terminé le défi avec succès !');
        }

        // Si c'est une requête AJAX (depuis le modal), retourner JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Participation mise à jour avec succès !',
                'participation' => $participation->fresh(['defi', 'livre', 'user'])
            ]);
        }

        return redirect()->route('participation-defis.show', $participation)
            ->with('success', 'Participation mise à jour avec succès !');
    }

    /**
     * Delete participation
     */
    public function destroy(ParticipationDefi $participation)
    {
        // Vérifier que l'utilisateur peut supprimer cette participation
        if ($participation->user_id !== Auth::id()) {
            abort(403);
        }

        $participation->delete();

        return redirect()->route('front.events.index')
            ->with('success', 'Participation supprimée avec succès.');
    }
}
