<?php

namespace App\Http\Controllers;

use App\Models\BibliothequeVirtuelle;
use App\Models\Discussion;
use App\Models\Livre;
use App\Models\LivreEmbedding;
use App\Services\GroqEmbeddingService;
use App\Services\BookTextExtractor;
use App\Services\SimilarityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Admin dashboard: list all bibliothèques (admin only).
     */
    public function dashboard()
    {
        $bibliotheques = BibliothequeVirtuelle::with(['user'])
            ->withCount(['livres', 'discussions'])
            ->latest()->get();
        $latestDiscussions = Discussion::with(['user', 'bibliotheque'])
            ->latest()->limit(10)->get();
        
        return view('admin.dashboard', compact('bibliotheques', 'latestDiscussions'));
    }

    /**
     * Delete a discussion (admin only).
     */
    public function deleteDiscussion($discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);
        $discussion->delete();
        return back()->with('success', 'Discussion deleted');
    }

    /**
     * View a specific bibliothèque and all its uploaded books/files (admin only).
     */
    public function bibliothequeShow($id)
    {
        $bibliotheque = BibliothequeVirtuelle::with(['livres', 'user'])->findOrFail($id);
        return view('admin.bibliotheques.show', compact('bibliotheque'));
    }

    /**
     * Admin view: discussions for a specific bibliotheque (read-only + delete, admin only).
     */
    public function bibliothequeDiscussions($id)
    {
        $bibliotheque = BibliothequeVirtuelle::findOrFail($id);
        $discussions = Discussion::with(['user'])
            ->where('bibliotheque_id', $id)
            ->withCount(['comments'])
            ->latest()->paginate(15);
        return view('admin.bibliotheques.discussions', compact('bibliotheque', 'discussions'));
    }

    // ==================== BIBLIOTHEQUES MANAGEMENT ====================

    /**
     * Display a listing of all bibliotheques (admin can manage all).
     */
    public function bibliothequesIndex()
    {
        $bibliotheques = BibliothequeVirtuelle::with(['user'])
            ->withCount('livreUtilisateurs')
            ->latest()
            ->get();

        return view('admin.bibliotheques.index', compact('bibliotheques'));
    }

    /**
     * Show the form for creating a new bibliotheque.
     */
    public function bibliothequesCreate()
    {
        return view('admin.bibliotheques.create');
    }

    /**
     * Store a newly created bibliotheque.
     */
    public function bibliothequesStore(Request $request)
    {
        $request->validate([
            'nom_bibliotheque' => ['required', 'string', 'max:255', 'unique:bibliotheque_virtuelles,nom_bibliotheque'],
        ]);

        $bibliotheque = BibliothequeVirtuelle::create([
            'nom_bibliotheque' => $request->nom_bibliotheque,
            'nb_livres' => 0,
            'user_id' => Auth::id(), // Admin creates as themselves
        ]);

        return redirect()->route('admin.bibliotheques.show', $bibliotheque->id)
            ->with('success', 'Library created successfully!');
    }

    /**
     * Display the specified bibliotheque.
     */
    public function bibliothequesShow(BibliothequeVirtuelle $bibliotheque)
    {
        $livres = $bibliotheque->livres()
            ->latest()
            ->get();

        return view('admin.bibliotheques.show', compact('bibliotheque', 'livres'));
    }

    /**
     * Show the form for editing the specified bibliotheque.
     */
    public function bibliothequesEdit(BibliothequeVirtuelle $bibliotheque)
    {
        return view('admin.bibliotheques.edit', compact('bibliotheque'));
    }

    /**
     * Update the specified bibliotheque.
     */
    public function bibliothequesUpdate(Request $request, BibliothequeVirtuelle $bibliotheque)
    {
        $request->validate([
            'nom_bibliotheque' => [
                'required',
                'string',
                'max:255',
                'unique:bibliotheque_virtuelles,nom_bibliotheque,' . $bibliotheque->id . ',id'
            ],
        ]);

        $bibliotheque->update([
            'nom_bibliotheque' => $request->nom_bibliotheque,
        ]);

        return redirect()->route('admin.bibliotheques.show', $bibliotheque->id)
            ->with('success', 'Library updated successfully!');
    }

    /**
     * Remove the specified bibliotheque.
     */
    public function bibliothequesDestroy(BibliothequeVirtuelle $bibliotheque)
    {
        // Delete all associated files and livres
        foreach ($bibliotheque->livres as $livre) {
            if ($livre->fichier_livre) {
                Storage::disk('public')->delete($livre->fichier_livre);
            }
        }

        $bibliotheque->livres()->delete();
        $bibliotheque->delete();

        return redirect()->route('admin.bibliotheques.index')
            ->with('success', 'Library deleted successfully!');
    }

    /**
     * Show the book selection page for adding books to a library.
     */
    public function bibliothequesAddBooks(BibliothequeVirtuelle $bibliotheque)
    {
        // Get all books that don't belong to this library (or have no library assigned)
        $availableBooks = Livre::where(function($query) use ($bibliotheque) {
                $query->where('bibliotheque_id', '!=', $bibliotheque->id)
                      ->orWhereNull('bibliotheque_id');
            })
            ->get();

        return view('admin.bibliotheques.add-books', compact('bibliotheque', 'availableBooks'));
    }

    /**
     * Store selected books to the library.
     */
    public function bibliothequesStoreBooks(Request $request, BibliothequeVirtuelle $bibliotheque)
    {
        $request->validate([
            'selected_books' => 'required|array|min:1',
            'selected_books.*' => 'exists:livres,id',
        ]);

        // Update selected books to belong to this library
        Livre::whereIn('id', $request->selected_books)
            ->update(['bibliotheque_id' => $bibliotheque->id]);

        // Update library book count
        $bibliotheque->increment('nb_livres', count($request->selected_books));

        return redirect()->route('admin.bibliotheques.show', $bibliotheque->id)
            ->with('success', count($request->selected_books) . ' book(s) added to library successfully!');
    }

    // ==================== LIVRES MANAGEMENT ====================

    /**
     * Display a listing of all livres (admin can manage all).
     */
    public function livresIndex()
    {
        $livres = Livre::with(['bibliotheque', 'user'])
            ->latest()
            ->get();
        return view('admin.livres.index', compact('livres'));
    }

    /**
     * Show the form for creating a new livre.
     */
    public function livresCreate(Request $request)
    {
        $bibliotheques = BibliothequeVirtuelle::all();
        $recentUploads = Livre::latest()->take(3)->get();
        return view('admin.livres.create', compact('bibliotheques', 'recentUploads'));
    }

    /**
     * Store a newly created livre.
     */
    public function livresStore(Request $request)
    {
        // Validation rules for creating new books only
        $validationRules = [
            'bibliotheque_id' => ['required', 'exists:bibliotheque_virtuelles,id'],
            'livre_id' => ['nullable', 'exists:livres,id'],
            'fichier_livre' => ['required', 'file', 'mimes:pdf,epub,mobi,txt', 'max:10240'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'format' => ['nullable', 'string', 'max:50'],
            'taille' => ['nullable', 'string', 'max:50'],
            'visibilite' => ['required', 'in:public,private'],
            'description' => ['nullable', 'string', 'max:1000'],
            'isbn' => ['nullable', 'string', 'max:20'],
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'langue' => ['nullable', 'string', 'max:10'],
            'nb_pages' => ['nullable', 'integer', 'min:0'],
            'resume' => ['nullable', 'string'],
            'etat' => ['nullable', 'string', 'max:50'],
            'defi_id' => ['nullable', 'exists:defis,id'],
        ];

        $request->validate($validationRules);

        // Verify the bibliotheque exists
        $bibliotheque = BibliothequeVirtuelle::findOrFail($request->bibliotheque_id);

        // Handle file upload
        $file = $request->file('fichier_livre');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('livres', $fileName, 'public');

        // Get file size and format
        $fileSize = $file->getSize();
        $fileSizeFormatted = $this->formatFileSize($fileSize);
        $fileFormat = $file->getClientOriginalExtension();

        // Check for plagiarism before creating new book
        $plagiarismResult = $this->checkForPlagiarism($file, $request);
        if ($plagiarismResult) {
            return back()->withErrors(['plagiarism' => $plagiarismResult])->withInput();
        }

        // Create new book with file upload
        $livre = Livre::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'categorie_id' => $request->categorie_id,
            'user_id' => Auth::id(), // Admin creates as themselves
            'bibliotheque_id' => $request->bibliotheque_id,
            'fichier_livre' => $filePath,
            'format' => $request->format ?? $fileFormat,
            'taille' => $request->taille ?? $fileSizeFormatted,
            'visibilite' => $request->visibilite,
            'user_description' => $request->description,
            'langue' => $request->langue ?? 'fr',
            'nb_pages' => $request->nb_pages ?? 0,
            'resume' => $request->resume ?? '',
            'etat' => $request->etat ?? 'neuf',
            'defi_id' => $request->defi_id,
        ]);

        // Update bibliotheque book count
        $bibliotheque->increment('nb_livres');

        // Generate and save embedding for the new book
        $this->saveBookEmbedding($livre, $file);

        return redirect()->route('admin.bibliotheques.show', $bibliotheque->id)
            ->with('success', 'Book uploaded successfully!');
    }

    /**
     * Display the specified livre.
     */
    public function livresShow(Livre $livre)
    {
        return view('admin.livres.show', compact('livre'));
    }

    /**
     * Show the form for editing the specified livre.
     */
    public function livresEdit(Livre $livre)
    {
        $bibliotheques = BibliothequeVirtuelle::all();
        return view('admin.livres.edit', compact('livre', 'bibliotheques'));
    }

    /**
     * Update the specified livre.
     */
    public function livresUpdate(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'categorie_id' => 'nullable|exists:categories,id',
            'format' => 'nullable|string|max:50',
            'visibilite' => 'required|in:public,private',
            'user_description' => 'nullable|string|max:1000',
            'fichier_livre' => 'nullable|file|mimes:pdf,epub,mobi,txt|max:10240',
            'bibliotheque_id' => 'nullable|exists:bibliotheque_virtuelles,id',
        ]);

        if ($request->hasFile('fichier_livre')) {
            // Delete old file if exists
            if ($livre->fichier_livre) {
                Storage::disk('public')->delete($livre->fichier_livre);
            }
            $validated['fichier_livre'] = $request->file('fichier_livre')->store('livres', 'public');
        }

        $livre->update($validated);
        return redirect()->route('admin.livres.show', $livre->id)->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified livre from storage.
     */
    public function livresDestroy(Livre $livre)
    {
        // Delete the associated file if it exists
        if ($livre->fichier_livre) {
            Storage::disk('public')->delete($livre->fichier_livre);
        }

        // Update bibliotheque book count if book was in a library
        if ($livre->bibliotheque_id) {
            $bibliotheque = $livre->bibliotheque;
            if ($bibliotheque) {
                $bibliotheque->decrement('nb_livres');
            }
        }

        // Delete the livre
        $livre->delete();

        return redirect()->route('admin.livres.index')
            ->with('success', 'Book deleted successfully!');
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
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'langue' => ['nullable', 'string', 'max:10'],
            'nb_pages' => ['nullable', 'integer', 'min:0'],
            'resume' => ['nullable', 'string'],
            'etat' => ['nullable', 'string', 'max:50'],
        ]);

        $livre = Livre::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'categorie_id' => $request->categorie_id,
            'langue' => $request->langue ?? 'fr',
            'nb_pages' => $request->nb_pages ?? 0,
            'resume' => $request->resume ?? '',
            'etat' => $request->etat ?? 'neuf',
            'user_id' => Auth::id(), // Admin creates as themselves
        ]);

        return response()->json([
            'success' => true,
            'book' => $livre
        ]);
    }

    // ==================== HELPER METHODS ====================

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

    /**
     * Check for plagiarism before creating a new book.
     * Returns error message if plagiarism detected, null if clean.
     */
    private function checkForPlagiarism($file, $request): ?string
    {
        $extractor = new BookTextExtractor();
        $embeddingService = new GroqEmbeddingService();
        $similarityService = new SimilarityService();

        // Extract text from uploaded file
        $text = $extractor->extractText($file);

        // Skip check if insufficient content
        if (!$extractor->hasSufficientContent($text)) {
            return null;
        }

        // Generate embedding for the uploaded text
        $embedding = $embeddingService->generateEmbedding($text);

        // Get all existing embeddings
        $existingEmbeddings = LivreEmbedding::with('livre')->get();

        // Find most similar book
        $similarBook = $similarityService->findMostSimilar($embedding['vector'], $existingEmbeddings);

        if ($similarBook && $similarityService->isSimilarityHigh($similarBook['similarity'], 0.85)) {
            // High similarity detected - return error message
            $similarLivre = $similarBook['embedding']->livre;
            $similarity = round($similarBook['similarity'] * 100, 1);

            return "⚠️ Plagiarism detected! This book is {$similarity}% similar to '{$similarLivre->title}' by {$similarLivre->author} in library '{$similarLivre->bibliotheque->nom_bibliotheque}'. Please ensure your content is original.";
        }

        return null;
    }

    /**
     * Save embedding for a book after creation/update.
     */
    private function saveBookEmbedding($livre, $file)
    {
        $extractor = new BookTextExtractor();
        $embeddingService = new GroqEmbeddingService();

        // Extract text from uploaded file
        $text = $extractor->extractText($file);

        // Skip if insufficient content
        if (!$extractor->hasSufficientContent($text)) {
            return;
        }

        // Generate embedding
        $embedding = $embeddingService->generateEmbedding($text);

        // Save or update embedding
        LivreEmbedding::updateOrCreate(
            ['livre_id' => $livre->id],
            [
                'embedding' => $embedding['vector'],
                'dimension' => $embedding['dimension'],
            ]
        );
    }
}
