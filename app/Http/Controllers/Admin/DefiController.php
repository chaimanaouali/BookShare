<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Defi;
use App\Models\Livre;
use App\Http\Requests\StoreDefiRequest;
use App\Http\Requests\UpdateDefiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DefiController extends Controller
{
    public function index()
    {
        $defis = Defi::withCount('livres')->orderByDesc('created_at')->paginate(10);
        return view('admin.defis.index', compact('defis'));
    }

    public function create()
    {
        return view('admin.defis.create');
    }

    public function store(StoreDefiRequest $request)
    {
        $data = $request->all();

        Defi::create($data);
        return redirect()->route('defis.index')->with('success', 'Défi créé avec succès.');
    }

    public function show(Defi $defi)
    {
        $defi->load('livres');
        return view('admin.defis.show', compact('defi'));
    }

    public function edit(Defi $defi)
    {
        return view('admin.defis.edit', compact('defi'));
    }

    public function update(UpdateDefiRequest $request, Defi $defi)
    {
        $data = $request->all();

        $defi->update($data);
        return redirect()->route('defis.index')->with('success', 'Défi mis à jour avec succès.');
    }

    public function destroy(Defi $defi)
    {
        $defi->delete();
        return redirect()->route('defis.index')->with('success', 'Défi supprimé.');
    }

    /**
     * Show the form to add books to a défi
     */
    public function addBooks(Defi $defi)
    {
        try {
            // Récupérer tous les livres disponibles (pas associés à d'autres défis)
            $availableBooks = Livre::where(function($query) use ($defi) {
                    $query->whereNull('defi_id')
                          ->orWhere('defi_id', $defi->id);
                })
                ->with(['user', 'categorie'])
                ->get();
            
            // Séparer les livres déjà associés de ceux disponibles
            $alreadyAssociated = $availableBooks->where('defi_id', $defi->id);
            $notAssociated = $availableBooks->whereNull('defi_id');
            
            return view('admin.defis.add-books', compact('defi', 'availableBooks', 'alreadyAssociated', 'notAssociated'));
        } catch (\Exception $e) {
            Log::error('Error in addBooks: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement des livres: ' . $e->getMessage());
        }
    }

    /**
     * Associate books with a défi
     */
    public function associateBooks(Request $request, Defi $defi)
    {
        // Debug: Log the request data
        Log::info('Associate books request:', [
            'defi_id' => $defi->id,
            'livre_ids' => $request->livre_ids,
            'all_data' => $request->all()
        ]);

        $request->validate([
            'livre_ids' => 'required|array',
            'livre_ids.*' => 'exists:livres,id',
        ]);

        // Remove books from other défis first
        Livre::whereIn('id', $request->livre_ids)
            ->update(['defi_id' => null]);

        // Associate books with this défi
        Livre::whereIn('id', $request->livre_ids)
            ->update(['defi_id' => $defi->id]);

        return redirect()->route('defis.show', $defi)
            ->with('success', 'Livres associés au défi avec succès.');
    }

    /**
     * Remove a book from a défi
     */
    public function removeBook(Defi $defi, Livre $livre)
    {
        if ($livre->defi_id === $defi->id) {
            $livre->update(['defi_id' => null]);
            return redirect()->route('defis.show', $defi)
                ->with('success', 'Livre retiré du défi avec succès.');
        }

        return redirect()->route('defis.show', $defi)
            ->with('error', 'Ce livre n\'est pas associé à ce défi.');
    }

    public function participants(Defi $defi)
    {
        $defi->load(['participations' => function($q){
            $q->with(['user','livre'])->orderByDesc('created_at');
        }]);
        return view('admin.defis.participants', compact('defi'));
    }
}
