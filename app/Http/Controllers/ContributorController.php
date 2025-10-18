<?php

namespace App\Http\Controllers;

use App\Models\BibliothequeVirtuelle;
use App\Models\Livre;
use App\Models\LivreEmbedding;
use App\Services\GroqEmbeddingService;
use App\Services\BookTextExtractor;
use App\Services\SimilarityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContributorController extends Controller
{

    /**
     * Show a single user's book.
     */
    public function livresShow(Livre $livre)
    {
        // Check if user is authenticated and owns this book
        if (!auth()->check() || $livre->user_id !== auth()->id()) {
            abort(403, 'You can only view your own books.');
        }
        return view('contributor.livres.show', compact('livre'));
    }

    /**
     * Show the form to create new book metadata
     */
    public function livresNew()
    {
        return view('contributor.livres.new');
    }

    /**
     * Handle the new book metadata form
     */
    public function livresStoreMetadata(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'publication_date' => 'nullable|date',
            'categorie_id' => 'nullable|exists:categories,id',
            'langue' => 'nullable|string|max:10',
            'nb_pages' => 'nullable|integer|min:0',
            'resume' => 'nullable|string',
            'etat' => 'nullable|string|max:50',
        ]);
        // Handle cover image upload if present
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }
        // Ensure defaults if not provided
        $validated['langue'] = $validated['langue'] ?? 'fr';
        $validated['nb_pages'] = $validated['nb_pages'] ?? 0;
        $validated['resume'] = $validated['resume'] ?? '';
        $validated['etat'] = $validated['etat'] ?? 'neuf';
        $validated['utilisateur_id'] = Auth::id();
        $livre = \App\Models\Livre::create($validated);
        return redirect()->route('contributor.livres.create')->with('success', 'Book created! You can now upload your file.');
    }

    /**
     * Update a user's book
     */
    public function livresUpdate(Request $request, Livre $livre)
    {
        // Check if user is authenticated and owns this book
        if (!auth()->check() || $livre->user_id !== auth()->id()) {
            abort(403, 'You can only edit your own books.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'categorie_id' => 'nullable|exists:categories,id',
            'format' => 'nullable|string|max:50',
            'visibilite' => 'required|in:public,private',
            'user_description' => 'nullable|string|max:1000',
            'fichier_livre' => 'nullable|file|mimes:pdf,epub,mobi,txt|max:10240',
        ]);
        if ($request->hasFile('fichier_livre')) {
            $validated['fichier_livre'] = $request->file('fichier_livre')->store('livres', 'public');
        }
        $livre->update($validated);
        return redirect()->route('contributor.livres.show', $livre->id)->with('success', 'Book updated successfully!');
    }

    /**
     * Show the edit form for a user's book.
     */
    public function livresEdit(Livre $livre)
    {
        // Check if user is authenticated and owns this book
        if (!auth()->check() || $livre->user_id !== auth()->id()) {
            abort(403, 'You can only edit your own books.');
        }
        return view('contributor.livres.edit', compact('livre'));
    }

    /**
     * Display a listing of the user's livres (books).
     */
    public function livresIndex()
    {
        $user = Auth::user();
        $livres = $user->livres()->with(['bibliotheque'])->latest()->get();
        return view('contributor.livres.index', compact('livres'));
    }

    /**
     * Display the contributor dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $bibliotheques = $user->bibliotheques()->withCount('livres')->latest()->take(3)->get();
        $totalBooks = $user->livres()->count();
        $publicBooks = $user->livres()->where('visibilite', 'public')->count();
        $recentBooks = $user->livres()
            ->with(['bibliotheque'])
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

        $livres = $bibliotheque->livres()
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

        // Delete all associated files and livres
        foreach ($bibliotheque->livres as $livre) {
            if ($livre->fichier_livre) {
                Storage::disk('public')->delete($livre->fichier_livre);
            }
        }

        $bibliotheque->livres()->delete();
        $bibliotheque->delete();

        return redirect()->route('contributor.bibliotheques.index')
            ->with('success', 'Library deleted successfully!');
    }

    /**
     * Show the book selection page for adding books to a library.
     */
    public function bibliothequesAddBooks(BibliothequeVirtuelle $bibliotheque)
    {
        // Ensure the bibliotheque belongs to the authenticated user
        if ($bibliotheque->user_id !== Auth::id()) {
            abort(403);
        }

        // Get all books that don't belong to this library (or have no library assigned)
        $availableBooks = Livre::where('user_id', Auth::id())
            ->where(function($query) use ($bibliotheque) {
                $query->where('bibliotheque_id', '!=', $bibliotheque->id)
                      ->orWhereNull('bibliotheque_id');
            })
            ->get();

        return view('contributor.bibliotheques.add-books', compact('bibliotheque', 'availableBooks'));
    }

    /**
     * Store selected books to the library.
     */
    public function bibliothequesStoreBooks(Request $request, BibliothequeVirtuelle $bibliotheque)
    {
        // Ensure the bibliotheque belongs to the authenticated user
        if ($bibliotheque->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'selected_books' => 'required|array|min:1',
            'selected_books.*' => 'exists:livres,id',
        ]);

        // Update selected books to belong to this library
        Livre::whereIn('id', $request->selected_books)
            ->where('user_id', Auth::id())
            ->update(['bibliotheque_id' => $bibliotheque->id]);

        // Update library book count
        $bibliotheque->increment('nb_livres', count($request->selected_books));

        return redirect()->route('contributor.bibliotheques.show', $bibliotheque->id)
            ->with('success', count($request->selected_books) . ' book(s) added to library successfully!');
    }

    /**
     * Show the form for creating a new livre.
     */
    public function livresCreate(Request $request)
    {
        $bibliotheques = Auth::user()->bibliotheques()->get();
        $recentUploads = Auth::user()->livres()->latest()->take(3)->get();
        return view('contributor.livres.create', compact('bibliotheques', 'recentUploads'));
    }

    /**
     * Store a newly created livre.
     */
public function livresStore(Request $request)
    {
        // Validation rules for creating new books only
        $validationRules = [
            'bibliotheque_id' => ['required', 'exists:bibliotheque_virtuelles,id'],
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
        ];

        $request->validate($validationRules);

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
                'user_id' => Auth::id(),
            'utilisateur_id' => Auth::id(),
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
            ]);

        // Update bibliotheque book count
        $bibliotheque->increment('nb_livres');

        // Generate and save embedding for the new book
        $this->saveBookEmbedding($livre, $file);

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
            'utilisateur_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'book' => $livre
        ]);
    }

    /**
     * Remove the specified livre from storage.
     */
    public function livresDestroy(Livre $livre)
    {
        // Check if user is authenticated and owns this book
        if (!auth()->check() || $livre->user_id !== auth()->id()) {
            abort(403, 'You can only delete your own books.');
        }

        // Delete the associated file if it exists
        if ($livre->fichier_livre) {
            Storage::disk('public')->delete($livre->fichier_livre);
        }

        // Delete the livre
        $livre->delete();

        return redirect()->route('contributor.livres.index')
            ->with('success', 'Book deleted successfully!');
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
