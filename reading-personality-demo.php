<?php

/**
 * Reading Personality Integration Test
 * 
 * This file demonstrates how to use the Reading Personality feature
 * that analyzes user borrowing history to create AI-generated profiles.
 */

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Services\ReadingPersonalityService;

// Example usage of the Reading Personality Service
class ReadingPersonalityDemo
{
    private $readingPersonalityService;

    public function __construct()
    {
        $this->readingPersonalityService = new ReadingPersonalityService();
    }

    /**
     * Generate reading personality for a user
     */
    public function generatePersonalityForUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return ['error' => 'User not found'];
        }

        // Check if user has enough borrowing history
        if (!$this->readingPersonalityService->hasEnoughHistory($user)) {
            return ['error' => 'User needs at least 3 completed borrowings'];
        }

        // Generate the personality
        $result = $this->readingPersonalityService->generateReadingPersonality($user);
        
        return $result;
    }

    /**
     * Get existing personality for a user
     */
    public function getPersonalityForUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return ['error' => 'User not found'];
        }

        $personality = $this->readingPersonalityService->getReadingPersonality($user);
        
        return [
            'user' => $user->name,
            'personality' => $personality,
            'has_personality' => $personality !== null
        ];
    }

    /**
     * Example of how the AI analyzes borrowing history
     */
    public function analyzeBorrowingPatterns($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return ['error' => 'User not found'];
        }

        // Get borrowing history
        $borrowings = Emprunt::where('utilisateur_id', $userId)
            ->with(['livre.categorie'])
            ->whereNotNull('date_retour_eff')
            ->orderBy('date_emprunt', 'desc')
            ->limit(10)
            ->get();

        $analysis = [
            'total_books' => $borrowings->count(),
            'genres' => $borrowings->pluck('livre.genre')->filter()->countBy()->toArray(),
            'categories' => $borrowings->pluck('livre.categorie.nom')->filter()->countBy()->toArray(),
            'average_borrowing_duration' => $borrowings->map(function($emprunt) {
                return $emprunt->date_emprunt->diffInDays($emprunt->date_retour_eff);
            })->avg(),
            'recent_books' => $borrowings->take(5)->map(function($emprunt) {
                return [
                    'title' => $emprunt->livre->title,
                    'author' => $emprunt->livre->author,
                    'genre' => $emprunt->livre->genre,
                    'borrowed_date' => $emprunt->date_emprunt->format('Y-m-d')
                ];
            })->toArray()
        ];

        return $analysis;
    }
}

// Example usage (commented out to prevent execution)
/*
$demo = new ReadingPersonalityDemo();

// Generate personality for user ID 1
$result = $demo->generatePersonalityForUser(1);
echo "Generation Result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";

// Get existing personality for user ID 1
$personality = $demo->getPersonalityForUser(1);
echo "Existing Personality: " . json_encode($personality, JSON_PRETTY_PRINT) . "\n";

// Analyze borrowing patterns for user ID 1
$analysis = $demo->analyzeBorrowingPatterns(1);
echo "Borrowing Analysis: " . json_encode($analysis, JSON_PRETTY_PRINT) . "\n";
*/

/**
 * API Endpoints Available:
 * 
 * GET /reading-personality - Show reading personality page
 * POST /reading-personality/generate - Generate new personality
 * POST /reading-personality/update - Update existing personality
 * GET /reading-personality/data - Get personality data as JSON
 * GET /reading-personality/user/{user} - Show user's personality (admin)
 * 
 * Example API Usage:
 * 
 * // Generate personality
 * fetch('/reading-personality/generate', {
 *     method: 'POST',
 *     headers: {
 *         'Content-Type': 'application/json',
 *         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
 *     }
 * })
 * .then(response => response.json())
 * .then(data => console.log(data));
 * 
 * // Get personality data
 * fetch('/reading-personality/data')
 * .then(response => response.json())
 * .then(data => console.log(data));
 */

/**
 * Database Schema:
 * 
 * reading_personalities table:
 * - id (primary key)
 * - user_id (foreign key to users)
 * - personality_title (e.g., "Explorateur curieux")
 * - personality_description (main description)
 * - reading_patterns (JSON: genres, themes, style, behavior)
 * - recommendations (JSON: array of book suggestions)
 * - challenge_suggestion (next reading challenge)
 * - books_analyzed (number of books analyzed)
 * - last_updated (timestamp)
 * - created_at, updated_at
 */

/**
 * AI Prompt Structure:
 * 
 * The AI analyzes:
 * - Book titles and authors
 * - Genres and categories
 * - Borrowing patterns and duration
 * - Reading frequency
 * 
 * And generates:
 * - Personality title (e.g., "Explorateur curieux")
 * - Detailed description
 * - Reading patterns (genres, themes, style)
 * - Book recommendations
 * - Reading challenges
 */
