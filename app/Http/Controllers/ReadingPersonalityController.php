<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReadingPersonality;
use App\Services\ReadingPersonalityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReadingPersonalityController extends Controller
{
    protected $readingPersonalityService;

    public function __construct(ReadingPersonalityService $readingPersonalityService)
    {
        $this->readingPersonalityService = $readingPersonalityService;
    }

    /**
     * Display the reading personality for the authenticated user
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get existing personality
        $personality = $this->readingPersonalityService->getReadingPersonality($user);
        
        // Check if user has enough borrowing history
        $hasEnoughHistory = $this->readingPersonalityService->hasEnoughHistory($user);
        
        return view('content.reading-personality.show', compact('personality', 'hasEnoughHistory'));
    }

    /**
     * Generate a new reading personality for the authenticated user
     */
    public function generate(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Check if user has enough borrowing history
            if (!$this->readingPersonalityService->hasEnoughHistory($user)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Pas assez d\'historique d\'emprunts pour générer un profil. Empruntez au moins 3 livres d\'abord!'
                ], 400);
            }

            // Validate API configuration
            if (!$this->readingPersonalityService->validateConfiguration()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Configuration API manquante. Veuillez contacter l\'administrateur.'
                ], 500);
            }

            // Generate personality
            $result = $this->readingPersonalityService->generateReadingPersonality($user);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'personality' => $result['personality'],
                    'books_analyzed' => $result['books_analyzed'],
                    'message' => 'Profil de lecture généré avec succès!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Reading Personality Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la génération du profil. Veuillez réessayer plus tard.'
            ], 500);
        }
    }

    /**
     * Update/refresh the reading personality for the authenticated user
     */
    public function update(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Check if user has enough borrowing history
            if (!$this->readingPersonalityService->hasEnoughHistory($user)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Pas assez d\'historique d\'emprunts pour générer un profil. Empruntez au moins 3 livres d\'abord!'
                ], 400);
            }

            // Generate new personality (this will update existing or create new)
            $result = $this->readingPersonalityService->generateReadingPersonality($user);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'personality' => $result['personality'],
                    'books_analyzed' => $result['books_analyzed'],
                    'message' => 'Profil de lecture mis à jour avec succès!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Reading Personality Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour du profil. Veuillez réessayer plus tard.'
            ], 500);
        }
    }

    /**
     * Get reading personality data as JSON (for AJAX requests)
     */
    public function getPersonalityData(): JsonResponse
    {
        try {
            $user = Auth::user();
            $personality = $this->readingPersonalityService->getReadingPersonality($user);
            $hasEnoughHistory = $this->readingPersonalityService->hasEnoughHistory($user);
            
            return response()->json([
                'success' => true,
                'personality' => $personality,
                'has_enough_history' => $hasEnoughHistory,
                'needs_update' => $personality ? $personality->needsUpdate() : true
            ]);

        } catch (\Exception $e) {
            Log::error('Reading Personality Data Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des données.'
            ], 500);
        }
    }

    /**
     * Display reading personality for a specific user (admin only)
     */
    public function showUser(User $user)
    {
        // Check if current user is admin or the user themselves
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $personality = $this->readingPersonalityService->getReadingPersonality($user);
        $hasEnoughHistory = $this->readingPersonalityService->hasEnoughHistory($user);
        
        return view('content.reading-personality.show-user', compact('personality', 'hasEnoughHistory', 'user'));
    }
}