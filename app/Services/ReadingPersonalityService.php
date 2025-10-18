<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReadingPersonality;
use App\Models\Emprunt;
use App\Models\Livre;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReadingPersonalityService
{
    private $client;
    private $apiKey;
    private $baseUrl;
    private $model;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = config('services.gemini.base_url');
        $this->model = config('services.gemini.model');
    }

    /**
     * Generate or update reading personality for a user
     */
    public function generateReadingPersonality(User $user): array
    {
        try {
            // Get user's borrowing history
            $borrowingHistory = $this->getUserBorrowingHistory($user);
            
            if (empty($borrowingHistory)) {
                return [
                    'success' => false,
                    'error' => 'Pas assez d\'historique d\'emprunts pour générer un profil. Empruntez quelques livres d\'abord!'
                ];
            }

            // Prepare data for AI analysis
            $analysisData = $this->prepareAnalysisData($user, $borrowingHistory);

            // Call Gemini API to generate personality
            $aiResponse = $this->callGeminiApi($analysisData);

            // Parse AI response and create/update personality
            $personality = $this->createOrUpdatePersonality($user, $aiResponse, count($borrowingHistory));

            return [
                'success' => true,
                'personality' => $personality,
                'books_analyzed' => count($borrowingHistory)
            ];

        } catch (\Exception $e) {
            Log::error('Reading Personality Generation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur lors de la génération du profil. Veuillez réessayer plus tard.'
            ];
        }
    }

    /**
     * Get user's borrowing history with book details
     */
    private function getUserBorrowingHistory(User $user): array
    {
        return Emprunt::where('utilisateur_id', $user->id)
            ->with(['livre.categorie'])
            ->whereNotNull('date_retour_eff') // Only completed borrowings
            ->orderBy('date_emprunt', 'desc')
            ->limit(10) // Limit to last 10 books for analysis
            ->get()
            ->map(function ($emprunt) {
                return [
                    'title' => $emprunt->livre->title ?? 'Titre inconnu',
                    'author' => $emprunt->livre->author ?? 'Auteur inconnu',
                    'genre' => $emprunt->livre->genre ?? 'Non spécifié',
                    'category' => $emprunt->livre->categorie->nom ?? 'Non catégorisé',
                    'description' => $emprunt->livre->description ?? '',
                    'borrowed_date' => $emprunt->date_emprunt->format('Y-m-d'),
                    'returned_date' => $emprunt->date_retour_eff ? $emprunt->date_retour_eff->format('Y-m-d') : 'Non retourné',
                    'borrowing_duration' => $emprunt->date_retour_eff ? $emprunt->date_emprunt->diffInDays($emprunt->date_retour_eff) : 0,
                ];
            })
            ->toArray();
    }

    /**
     * Prepare data for AI analysis
     */
    private function prepareAnalysisData(User $user, array $borrowingHistory): string
    {
        $data = "Lecteur: {$user->name}\n\n";
        $data .= "Livres empruntés:\n";
        
        foreach ($borrowingHistory as $book) {
            $data .= "- {$book['title']} ({$book['author']}) - {$book['genre']}\n";
        }

        // Add genre analysis (simplified)
        $genreValues = array_filter(array_column($borrowingHistory, 'genre'), function($value) {
            return !is_null($value) && $value !== '';
        });
        
        $genres = array_count_values($genreValues);
        
        $data .= "\nGenres préférés:\n";
        if (!empty($genres)) {
            foreach ($genres as $genre => $count) {
                $data .= "- {$genre}: {$count}\n";
            }
        }

        return $data;
    }

    /**
     * Call Gemini API to generate reading personality
     */
    private function callGeminiApi(string $analysisData): array
    {
        $prompt = "Analyse cet historique d'emprunts et crée un profil de lecteur en français. Réponds UNIQUEMENT avec ce JSON:

{
  \"personality_title\": \"Titre court (ex: 'Explorateur curieux')\",
  \"personality_description\": \"Description courte de 2 phrases\",
  \"reading_patterns\": {
    \"favorite_genres\": [\"genre1\", \"genre2\"],
    \"reading_themes\": [\"thème1\", \"thème2\"],
    \"reading_style\": \"style de lecture\",
    \"borrowing_behavior\": \"comportement d'emprunt\"
  },
  \"recommendations\": [\"Livre 1\", \"Livre 2\", \"Livre 3\"],
  \"challenge_suggestion\": \"Défi de lecture\"
}

Historique:
{$analysisData}";

        $requestBody = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 3000,
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ];

        $url = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";
        
        Log::info('Reading Personality Gemini API Request', [
            'url' => $url,
            'model' => $this->model,
            'content_length' => strlen($analysisData)
        ]);

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
                'timeout' => 30
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);

            Log::info('Reading Personality Gemini API Response', [
                'status_code' => $response->getStatusCode(),
                'response_data' => $responseData
            ]);

            // Extract text from response
            $aiText = '';
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $aiText = trim($responseData['candidates'][0]['content']['parts'][0]['text']);
            } elseif (isset($responseData['candidates'][0]['content']['parts'])) {
                $parts = $responseData['candidates'][0]['content']['parts'];
                foreach ($parts as $part) {
                    if (isset($part['text'])) {
                        $aiText .= trim($part['text']);
                    }
                }
            }

            if (empty($aiText)) {
                // Check if response was truncated due to token limit
                if (isset($responseData['candidates'][0]['finishReason']) && 
                    $responseData['candidates'][0]['finishReason'] === 'MAX_TOKENS') {
                    throw new \Exception('Response truncated due to token limit. Please try with less data.');
                }
                throw new \Exception('Empty response from Gemini API');
            }

            // Parse JSON response
            $personalityData = json_decode($aiText, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If JSON parsing fails, try to extract JSON from the text
                preg_match('/\{.*\}/s', $aiText, $matches);
                if (!empty($matches[0])) {
                    $personalityData = json_decode($matches[0], true);
                }
            }

            if (!$personalityData || json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from AI: ' . $aiText);
            }

            return $personalityData;

        } catch (RequestException $e) {
            Log::error('Reading Personality Gemini API Request Exception', [
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    /**
     * Create or update reading personality in database
     */
    private function createOrUpdatePersonality(User $user, array $aiResponse, int $booksAnalyzed): ReadingPersonality
    {
        return DB::transaction(function () use ($user, $aiResponse, $booksAnalyzed) {
            // Check if user already has a recent personality
            $existingPersonality = ReadingPersonality::where('user_id', $user->id)
                ->where('last_updated', '>=', now()->subDays(7))
                ->first();

            if ($existingPersonality) {
                // Update existing personality
                $existingPersonality->update([
                    'personality_title' => $aiResponse['personality_title'],
                    'personality_description' => $aiResponse['personality_description'],
                    'reading_patterns' => $aiResponse['reading_patterns'],
                    'recommendations' => $aiResponse['recommendations'],
                    'challenge_suggestion' => $aiResponse['challenge_suggestion'],
                    'books_analyzed' => $booksAnalyzed,
                    'last_updated' => now(),
                ]);
                
                return $existingPersonality;
            } else {
                // Create new personality
                return ReadingPersonality::create([
                    'user_id' => $user->id,
                    'personality_title' => $aiResponse['personality_title'],
                    'personality_description' => $aiResponse['personality_description'],
                    'reading_patterns' => $aiResponse['reading_patterns'],
                    'recommendations' => $aiResponse['recommendations'],
                    'challenge_suggestion' => $aiResponse['challenge_suggestion'],
                    'books_analyzed' => $booksAnalyzed,
                    'last_updated' => now(),
                ]);
            }
        });
    }

    /**
     * Get existing reading personality for user
     */
    public function getReadingPersonality(User $user): ?ReadingPersonality
    {
        return ReadingPersonality::latestForUser($user->id)->first();
    }

    /**
     * Check if user has enough borrowing history for analysis
     */
    public function hasEnoughHistory(User $user): bool
    {
        $completedBorrowings = Emprunt::where('utilisateur_id', $user->id)
            ->whereNotNull('date_retour_eff')
            ->count();
            
        return $completedBorrowings >= 3; // Minimum 3 books for analysis
    }

    /**
     * Validate API configuration
     */
    public function validateConfiguration(): bool
    {
        return !empty($this->apiKey) && !empty($this->baseUrl) && !empty($this->model);
    }
}
