<?php

namespace App\Http\Controllers;

use App\Models\BibliothequeVirtuelle;
use App\Models\Livre;
use App\Models\LivreUtilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContributorController extends Controller
{
    
    /**
     * Show a single user's book (livre utilisateur).
     */
    public function livresShow(\App\Models\LivreUtilisateur $livreUtilisateur)
    {
        // Optionally: authorize that the user owns this book
        if ($livreUtilisateur->user_id !== auth()->id()) {
            abort(403);
        }
        return view('contributor.livres.show', compact('livreUtilisateur'));
    }

    /**
     * Show the edit form for a user's book (livre utilisateur).
     */
    public function livresEdit(\App\Models\LivreUtilisateur $livreUtilisateur)
    {
        if ($livreUtilisateur->user_id !== auth()->id()) {
            abort(403);
        }
        return view('contributor.livres.edit', compact('livreUtilisateur'));
    }

    /**
     * Display a listing of the user's livres (books).
     */
    public function livresIndex()
    {
        $user = Auth::user();
        $livres = $user->livreUtilisateurs()->with(['livre', 'bibliotheque'])->latest()->get();
        return view('contributor.livres.index', compact('livres'));
    }

    /**
     * Display the contributor dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        $bibliotheques = $user->bibliotheques()->withCount('livreUtilisateurs')->latest()->take(3)->get();
        $totalBooks = $user->livreUtilisateurs()->count();
        $publicBooks = $user->livreUtilisateurs()->where('visibilite', 'public')->count();
        $recentBooks = $user->livreUtilisateurs()
            ->with(['livre', 'bibliotheque'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('contributor.dashboard', compact(
            'bibliotheques', 
            'totalBooks', 
            'publicBooks', 
            'recentBooks'
        ));
    }
    
    /**
     * Display a listing of the user's bibliotheques.
     */
    public function bibliothequesIndex()
    {
        $bibliotheques = Auth::user()->bibliotheques()
            ->withCount('livreUtilisateurs')
            ->latest()
            ->get();
            
        return view('contributor.bibliotheques.index', compact('bibliotheques'));
    }
    
    /**
     * Show the form for creating a new bibliotheque.
     */
    public function bibliothequesCreate()
    {
        return view('contributor.bibliotheques.create');
    }
    
    /**
     * Store a newly created bibliotheque.
     */
    public function bibliothequesStore(Request $request)
    {
        $request->validate([
            'nom_bibliotheque' => ['required', 'string', 'max:255', 'unique:bibliotheque_virtuelles,nom_bibliotheque,NULL,id,user_id,' . Auth::id()],
        ]);
        
        $bibliotheque = Auth::user()->bibliotheques()->create([
            'nom_bibliotheque' => $request->nom_bibliotheque,
            'nb_livres' => 0,
        ]);
        
        return redirect()->route('contributor.bibliotheques.show', $bibliotheque->id)
            ->with('success', 'Library created successfully!');
    }
    
    /**
     * Display the specified bibliotheque.
     */
    public function bibliothequesShow(BibliothequeVirtuelle $bibliotheque)
    {
        // Ensure the bibliotheque belongs to the authenticated user
        if ($bibliotheque->user_id !== Auth::id()) {
            abort(403);
        }
        
        $livres = $bibliotheque->livreUtilisateurs()
            ->with('livre')
            ->latest()
            ->get();
            
        return view('contributor.bibliotheques.show', compact('bibliotheque', 'livres'));
    }
    
    /**
     * Show the form for editing the specified bibliotheque.
     */
    public function bibliothequesEdit(BibliothequeVirtuelle $bibliotheque)
    {
        // Ensure the bibliotheque belongs to the authenticated user
        if ($bibliotheque->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('contributor.bibliotheques.edit', compact('bibliotheque'));
    }
    
    /**
     * Update the specified bibliotheque.
     */
    public function bibliothequesUpdate(Request $request, BibliothequeVirtuelle $bibliotheque)
    {
        // Ensure the bibliotheque belongs to the authenticated user
        if ($bibliotheque->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'nom_bibliotheque' => [
                'required', 
                'string', 
                'max:255', 
                'unique:bibliotheque_virtuelles,nom_bibliotheque,' . $bibliotheque->id . ',id,user_id,' . Auth::id()
            ],
        ]);
        
        $bibliotheque->update([
            'nom_bibliotheque' => $request->nom_bibliotheque,
        ]);
        
        return redirect()->route('contributor.bibliotheques.show', $bibliotheque->id)
            ->with('success', 'Library updated successfully!');
    }
    
    /**
     * Remove the specified bibliotheque.
     */
    public function bibliothequesDestroy(BibliothequeVirtuelle $bibliotheque)
    {
        // Ensure the bibliotheque belongs to the authenticated user
        if ($bibliotheque->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Delete all associated files and livre utilisateurs
        foreach ($bibliotheque->livreUtilisateurs as $livreUtilisateur) {
            if ($livreUtilisateur->fichier_livre) {
                Storage::disk('public')->delete($livreUtilisateur->fichier_livre);
            }
        }
        
        $bibliotheque->livreUtilisateurs()->delete();
        $bibliotheque->delete();
        
        return redirect()->route('contributor.bibliotheques.index')
            ->with('success', 'Library deleted successfully!');
    }
    
    /**
     * Show the form for creating a new livre.
     */
    public function livresCreate(Request $request)
    {
        $bibliotheques = Auth::user()->bibliotheques()->get();
        $livres = \App\Models\Livre::all();
        $recentUploads = Auth::user()->livreUtilisateurs()->latest()->take(3)->get();
        return view('contributor.livres.create', compact('bibliotheques', 'livres', 'recentUploads'));
    }
    
    /**
     * Store a newly created livre.
     */
    public function livresStore(Request $request)
    {
        $request->validate([
            'bibliotheque_id' => ['required', 'exists:bibliotheque_virtuelles,id'],
            'livre_id' => ['required', 'exists:livres,id'],
            'fichier_livre' => ['required', 'file', 'mimes:pdf,epub,mobi,txt', 'max:10240'],
            'format' => ['nullable', 'string', 'max:50'],
            'taille' => ['nullable', 'string', 'max:50'],
            'visibilite' => ['required', 'in:public,private'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        
        // Verify the bibliotheque belongs to the authenticated user
        $bibliotheque = Auth::user()->bibliotheques()->findOrFail($request->bibliotheque_id);
        
        // Handle file upload
        $file = $request->file('fichier_livre');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('livres', $fileName, 'public');
        
        // Get file size and format
        $fileSize = $file->getSize();
        $fileSizeFormatted = $this->formatFileSize($fileSize);
        $fileFormat = $file->getClientOriginalExtension();
        
        $livreUtilisateur = LivreUtilisateur::create([
            'user_id' => Auth::id(),
            'bibliotheque_id' => $request->bibliotheque_id,
            'livre_id' => $request->livre_id,
            'fichier_livre' => $filePath,
            'format' => $request->format ?? $fileFormat,
            'taille' => $request->taille ?? $fileSizeFormatted,
            'visibilite' => $request->visibilite,
            'description' => $request->description,
        ]);
        
        // Update bibliotheque book count
        $bibliotheque->increment('nb_livres');
        
        return redirect()->route('contributor.bibliotheques.show', $bibliotheque->id)
            ->with('success', 'Book uploaded successfully!');
    }
    
    /**
     * Create a new book entry.
     */
    public function createBook(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        
        $livre = Livre::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'description' => $request->description,
        ]);
        
        return response()->json([
            'success' => true,
            'book' => $livre
        ]);
    }
    
    /**
     * Format file size in human readable format.
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
