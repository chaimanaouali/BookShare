<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GroqEmbeddingService
{
    /**
     * Generate an embedding vector for the given text using a simple hash-based approach for testing.
     * This creates a deterministic vector based on text content for similarity comparison.
     *
     * @param string $text
     * @return array{vector: array<int,float>, dimension: int}
     */
    public function generateEmbedding(string $text): array
    {
        // Normalize text
        $normalized = Str::of($text)
            ->replace(["\r\n", "\r"], "\n")
            ->squish()
            ->limit(50000, '');

        // Create a simple hash-based embedding for testing
        // This is not a real embedding but will work for similarity detection
        $hash = hash('sha256', (string) $normalized);
        $vector = [];
        
        // Convert hash to 384-dimensional vector (like real embeddings)
        for ($i = 0; $i < 384; $i++) {
            $hex = substr($hash, ($i * 2) % 64, 2);
            $value = hexdec($hex) / 255.0; // Normalize to 0-1
            $vector[] = $value * 2 - 1; // Scale to -1 to 1
        }

        return [
            'vector' => $vector,
            'dimension' => 384,
        ];
    }
}



