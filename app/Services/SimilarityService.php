<?php

namespace App\Services;

use Illuminate\Support\Collection;

class SimilarityService
{
    /**
     * Calculate cosine similarity between two vectors.
     *
     * @param array $vectorA
     * @param array $vectorB
     * @return float Similarity score between 0 and 1 (1 = identical)
     */
    public function cosineSimilarity(array $vectorA, array $vectorB): float
    {
        if (count($vectorA) !== count($vectorB)) {
            throw new \InvalidArgumentException('Vectors must have the same dimension');
        }

        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($vectorA); $i++) {
            $dotProduct += $vectorA[$i] * $vectorB[$i];
            $normA += $vectorA[$i] * $vectorA[$i];
            $normB += $vectorB[$i] * $vectorB[$i];
        }

        if ($normA == 0 || $normB == 0) {
            return 0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Find the most similar embedding from a collection.
     *
     * @param array $queryVector
     * @param Collection $embeddings
     * @return array|null Returns ['embedding' => LivreEmbedding, 'similarity' => float] or null
     */
    public function findMostSimilar(array $queryVector, Collection $embeddings): ?array
    {
        if ($embeddings->isEmpty()) {
            return null;
        }

        $bestMatch = null;
        $bestSimilarity = -1;

        foreach ($embeddings as $embedding) {
            $similarity = $this->cosineSimilarity($queryVector, $embedding->embedding);
            
            if ($similarity > $bestSimilarity) {
                $bestSimilarity = $similarity;
                $bestMatch = $embedding;
            }
        }

        return $bestMatch ? [
            'embedding' => $bestMatch,
            'similarity' => $bestSimilarity,
        ] : null;
    }

    /**
     * Check if similarity is above threshold (indicating potential plagiarism).
     *
     * @param float $similarity
     * @param float $threshold
     * @return bool
     */
    public function isSimilarityHigh(float $similarity, float $threshold = 0.85): bool
    {
        return $similarity >= $threshold;
    }
}
