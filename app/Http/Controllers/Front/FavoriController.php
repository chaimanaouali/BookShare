<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Favori;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriController extends Controller
{
    /**
     * Display the user's favorites.
     */
    public function index(): View
    {
        $user = Auth::user();
        $favoris = $user->livresFavoris()
                       ->with(['user', 'categorie', 'avis' => function($query) {
                           $query->select('livre_id', 'note');
                       }])
                       ->paginate(12);

        return view('front.favoris.index', compact('favoris'));
    }

    /**
     * Toggle favorite status for a book.
     */
    public function toggle(Request $request, Livre $livre): JsonResponse|RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            return redirect()->back()->with('error', 'Vous devez être connecté pour ajouter aux favoris.');
        }

        $isFavorited = Favori::toggle($user->id, $livre->id);
        
        $message = $isFavorited 
            ? 'Livre ajouté aux favoris avec succès.' 
            : 'Livre retiré des favoris avec succès.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'message' => $message,
                'favoris_count' => $livre->favoris_count
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Add a book to favorites.
     */
    public function store(Request $request, Livre $livre): JsonResponse|RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            return redirect()->back()->with('error', 'Vous devez être connecté pour ajouter aux favoris.');
        }

        // Check if already favorited
        if (Favori::isFavorited($user->id, $livre->id)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Ce livre est déjà dans vos favoris.'], 400);
            }
            return redirect()->back()->with('error', 'Ce livre est déjà dans vos favoris.');
        }

        Favori::create([
            'user_id' => $user->id,
            'livre_id' => $livre->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Livre ajouté aux favoris avec succès.',
                'favoris_count' => $livre->favoris_count
            ]);
        }

        return redirect()->back()->with('success', 'Livre ajouté aux favoris avec succès.');
    }

    /**
     * Remove a book from favorites.
     */
    public function destroy(Request $request, Livre $livre): JsonResponse|RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            return redirect()->back()->with('error', 'Vous devez être connecté pour retirer des favoris.');
        }

        $favori = Favori::where('user_id', $user->id)
                        ->where('livre_id', $livre->id)
                        ->first();

        if (!$favori) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Ce livre n\'est pas dans vos favoris.'], 400);
            }
            return redirect()->back()->with('error', 'Ce livre n\'est pas dans vos favoris.');
        }

        $favori->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Livre retiré des favoris avec succès.',
                'favoris_count' => $livre->favoris_count
            ]);
        }

        return redirect()->back()->with('success', 'Livre retiré des favoris avec succès.');
    }

    /**
     * Check if a book is favorited by the current user.
     */
    public function check(Request $request, Livre $livre): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['is_favorited' => false]);
        }

        $isFavorited = Favori::isFavorited($user->id, $livre->id);
        
        return response()->json([
            'is_favorited' => $isFavorited,
            'favoris_count' => $livre->favoris_count
        ]);
    }

    /**
     * Get favorites count for a book.
     */
    public function count(Livre $livre): JsonResponse
    {
        return response()->json([
            'favoris_count' => $livre->favoris_count
        ]);
    }
}
