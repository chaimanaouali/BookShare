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

    /**
     * Admin: Display a listing of all historique emprunts with filters
     */
    public function adminIndex(Request $request)
    {
        $query = HistoriqueEmprunt::with(['emprunt.livre', 'utilisateur']);

        // Filter by user if specified
        if ($request->filled('user_id')) {
            $query->where('utilisateur_id', $request->user_id);
        }

        // Filter by action if specified
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->whereDate('date_action', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_action', '<=', $request->date_to);
        }

        // Search by book title
        if ($request->filled('search')) {
            $query->whereHas('emprunt.livre', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        // Check if CSV export is requested
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportToCSV($query);
        }

        $historiqueEmprunts = $query->orderBy('date_action', 'desc')->paginate(20);

        // Get all users for filter dropdown
        $users = \App\Models\User::select('id', 'name', 'email')->get();

        // Get unique actions for filter dropdown
        $actions = HistoriqueEmprunt::select('action')->distinct()->pluck('action');

        return view('admin.historique-emprunts.index', compact('historiqueEmprunts', 'users', 'actions'));
    }

    /**
     * Admin: Display the specified historique emprunt
     */
    public function adminShow(HistoriqueEmprunt $historiqueEmprunt)
    {
        $historiqueEmprunt->load(['emprunt.livre', 'utilisateur']);
        
        return view('admin.historique-emprunts.show', compact('historiqueEmprunt'));
    }

    /**
     * Export historique emprunts to CSV
     */
    private function exportToCSV($query)
    {
        $historiqueEmprunts = $query->orderBy('date_action', 'desc')->get();
        
        $filename = 'historique-emprunts-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        $callback = function() use ($historiqueEmprunts) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($file, [
                'ID Historique',
                'Utilisateur',
                'Email Utilisateur',
                'Rôle Utilisateur',
                'ID Emprunt',
                'Titre Livre',
                'Auteur Livre',
                'Action',
                'Date Action',
                'Détails',
                'Statut Emprunt',
                'Date Emprunt',
                'Date Retour Prévue',
                'Date Retour Effectif',
                'Pénalité'
            ]);

            // CSV Data
            foreach ($historiqueEmprunts as $historique) {
                fputcsv($file, [
                    $historique->id,
                    $historique->utilisateur->name ?? 'N/A',
                    $historique->utilisateur->email ?? 'N/A',
                    $historique->utilisateur->role ?? 'user',
                    $historique->emprunt->id ?? 'N/A',
                    $historique->emprunt->livre->title ?? 'N/A',
                    $historique->emprunt->livre->author ?? 'N/A',
                    $historique->action,
                    $historique->date_action->format('Y-m-d H:i:s'),
                    $historique->details ?? '',
                    $historique->emprunt->statut ?? 'N/A',
                    $historique->emprunt->date_emprunt ? $historique->emprunt->date_emprunt->format('Y-m-d H:i:s') : 'N/A',
                    $historique->emprunt->date_retour_prev ? $historique->emprunt->date_retour_prev->format('Y-m-d H:i:s') : 'N/A',
                    $historique->emprunt->date_retour_eff ? $historique->emprunt->date_retour_eff->format('Y-m-d H:i:s') : 'N/A',
                    $historique->emprunt->penalite ?? 0
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
