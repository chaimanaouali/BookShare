<?php

namespace App\Services;

use App\Models\Avis;
use App\Models\Livre;
use App\Models\Recommendation;
use App\Models\User;
use App\Models\Categorie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    private $openaiApiKey;
    private $openaiBaseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
        $this->openaiBaseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
    }

    /**
     * Generate AI-based recommendations for a user based on their good ratings
     */
    public function generateAiRecommendations(User $user, int $limit = 5): array
    {
        // Get user's good ratings (4+ stars)
        $goodRatings = Avis::where('user_id', $user->id)
            ->where('note', '>=', 4)
            ->with(['livre.categorie'])
            ->get();

        if ($goodRatings->isEmpty()) {
            return [];
        }

        $recommendations = [];

        foreach ($goodRatings as $rating) {
            $categoryId = $rating->livre->categorie_id;
            
            // Find books in the same category that the user hasn't rated
            $suggestedBooks = Livre::where('categorie_id', $categoryId)
                ->where('id', '!=', $rating->livre_id)
                ->whereNotIn('id', function($query) use ($user) {
                    $query->select('livre_id')
                        ->from('avis')
                        ->where('user_id', $user->id);
                })
                ->where('disponibilite', true)
                ->limit($limit)
                ->get();

            foreach ($suggestedBooks as $book) {
                // Use AI to generate recommendation reason
                $reason = $this->generateAiReason($user, $rating->livre, $book);
                
                // Calculate recommendation score
                $score = $this->calculateRecommendationScore($rating, $book);

                $recommendations[] = [
                    'user_id' => $user->id,
                    'livre_id' => $book->id,
                    'avis_id' => $rating->id,
                    'score' => $score,
                    'source' => 'AI',
                    'reason' => $reason,
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Generate AI-based reason for recommendation
     */
    private function generateAiReason(User $user, Livre $likedBook, Livre $recommendedBook): string
    {
        if (!$this->openaiApiKey) {
            return "Recommended because you liked '{$likedBook->title}' in the same category.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->openaiBaseUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a book recommendation assistant. Generate a brief, personalized reason for recommending a book based on a user\'s previous positive rating.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "User liked '{$likedBook->title}' by {$likedBook->author} (rated highly). Recommend '{$recommendedBook->title}' by {$recommendedBook->author} in the same category. Generate a brief, engaging reason (max 100 words)."
                    ]
                ],
                'max_tokens' => 150,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? "Recommended based on your reading preferences.";
            }
        } catch (\Exception $e) {
            Log::error('OpenAI API error: ' . $e->getMessage());
        }

        return "Recommended because you liked '{$likedBook->title}' in the same category.";
    }

    /**
     * Calculate recommendation score based on various factors
     */
    private function calculateRecommendationScore(Avis $rating, Livre $book): float
    {
        $baseScore = 0.5; // Base score
        
        // Factor in the user's rating
        $ratingScore = ($rating->note / 5.0) * 0.3;
        
        // Factor in book availability and condition
        $availabilityScore = $book->disponibilite ? 0.2 : 0.0;
        
        // Factor in book popularity (number of reviews)
        $popularityScore = min($book->avis()->count() / 10.0, 0.2);
        
        return min($baseScore + $ratingScore + $availabilityScore + $popularityScore, 1.0);
    }

    /**
     * Save recommendations to database
     */
    public function saveRecommendations(array $recommendations): void
    {
        foreach ($recommendations as $recommendation) {
            // Check if recommendation already exists
            $exists = Recommendation::where('user_id', $recommendation['user_id'])
                ->where('livre_id', $recommendation['livre_id'])
                ->exists();

            if (!$exists) {
                Recommendation::create($recommendation);
            }
        }
    }

    /**
     * Get recommendations for a user
     */
    public function getUserRecommendations(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Recommendation::where('user_id', $user->id)
            ->with(['livre.categorie', 'avis'])
            ->orderBy('score', 'desc')
            ->orderBy('date_creation', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Generate collaborative recommendations based on similar users
     */
    public function generateCollaborativeRecommendations(User $user, int $limit = 5): array
    {
        // Find users with similar rating patterns
        $similarUsers = $this->findSimilarUsers($user);
        
        if (empty($similarUsers)) {
            return [];
        }

        $recommendations = [];
        
        foreach ($similarUsers as $similarUser) {
            // Get books that similar user liked but current user hasn't rated
            $suggestedBooks = Livre::whereIn('id', function($query) use ($similarUser) {
                $query->select('livre_id')
                    ->from('avis')
                    ->where('user_id', $similarUser['user_id'])
                    ->where('note', '>=', 4);
            })
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('livre_id')
                    ->from('avis')
                    ->where('user_id', $user->id);
            })
            ->where('disponibilite', true)
            ->limit($limit)
            ->get();

            foreach ($suggestedBooks as $book) {
                $recommendations[] = [
                    'user_id' => $user->id,
                    'livre_id' => $book->id,
                    'avis_id' => null,
                    'score' => $similarUser['similarity'] * 0.8, // Weight by similarity
                    'source' => 'collaborative',
                    'reason' => "Users with similar tastes also enjoyed this book.",
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Find users with similar rating patterns
     */
    private function findSimilarUsers(User $user): array
    {
        $userRatings = Avis::where('user_id', $user->id)->get();
        
        if ($userRatings->isEmpty()) {
            return [];
        }

        $similarUsers = [];
        
        // Get all other users who have rated books
        $otherUsers = User::where('id', '!=', $user->id)
            ->whereHas('avis')
            ->get();

        foreach ($otherUsers as $otherUser) {
            $otherRatings = Avis::where('user_id', $otherUser->id)->get();
            $similarity = $this->calculateUserSimilarity($userRatings, $otherRatings);
            
            if ($similarity > 0.3) { // Threshold for similarity
                $similarUsers[] = [
                    'user_id' => $otherUser->id,
                    'similarity' => $similarity,
                ];
            }
        }

        // Sort by similarity and return top 5
        usort($similarUsers, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return array_slice($similarUsers, 0, 5);
    }

    /**
     * Calculate similarity between two users based on their ratings
     */
    private function calculateUserSimilarity($userRatings, $otherRatings): float
    {
        $commonBooks = [];
        
        foreach ($userRatings as $userRating) {
            $otherRating = $otherRatings->where('livre_id', $userRating->livre_id)->first();
            if ($otherRating) {
                $commonBooks[] = [
                    'user_rating' => $userRating->note,
                    'other_rating' => $otherRating->note,
                ];
            }
        }

        if (count($commonBooks) < 2) {
            return 0;
        }

        // Calculate Pearson correlation coefficient
        $n = count($commonBooks);
        $sum1 = array_sum(array_column($commonBooks, 'user_rating'));
        $sum2 = array_sum(array_column($commonBooks, 'other_rating'));
        $sum1Sq = array_sum(array_map(function($x) { return $x['user_rating'] * $x['user_rating']; }, $commonBooks));
        $sum2Sq = array_sum(array_map(function($x) { return $x['other_rating'] * $x['other_rating']; }, $commonBooks));
        $pSum = array_sum(array_map(function($x) { return $x['user_rating'] * $x['other_rating']; }, $commonBooks));

        $num = $pSum - ($sum1 * $sum2 / $n);
        $den = sqrt(($sum1Sq - $sum1 * $sum1 / $n) * ($sum2Sq - $sum2 * $sum2 / $n));

        if ($den == 0) {
            return 0;
        }

        return $num / $den;
    }

    /**
     * Generate recommendations when a user rates a book highly
     */
    public function generateRecommendationsOnRating(Avis $avis): void
    {
        if ($avis->note >= 4) {
            // Use the defined relation name on Avis (utilisateur)
            $user = $avis->utilisateur;
            $book = $avis->livre;
            
            // Generate AI recommendations
            $aiRecommendations = $this->generateAiRecommendations($user, 3);
            $this->saveRecommendations($aiRecommendations);
            
            // Generate collaborative recommendations
            $collaborativeRecommendations = $this->generateCollaborativeRecommendations($user, 2);
            $this->saveRecommendations($collaborativeRecommendations);
        }
    }
}
