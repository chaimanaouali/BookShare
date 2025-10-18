<?php

namespace App\Services;

use App\Models\Discussion;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class AiSummaryService
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
     * Generate a summary for a discussion
     */
    public function summarizeDiscussion(Discussion $discussion): array
    {
        try {
            // Get all comments for the discussion
            $comments = $discussion->comments()
                ->with('user')
                ->orderBy('created_at')
                ->get();

            if ($comments->isEmpty()) {
                return [
                    'success' => false,
                    'error' => 'No comments found in this discussion'
                ];
            }

            // Prepare the discussion content for AI
            $discussionContent = $this->prepareDiscussionContent($discussion, $comments);

            // Check token limit (Gemini 1.5 Flash has ~1M token limit)
            if ($this->exceedsTokenLimit($discussionContent)) {
                $discussionContent = $this->truncateContent($discussionContent);
            }

            // Call Gemini API
            $summary = $this->callGeminiApi($discussionContent);

            return [
                'success' => true,
                'summary' => $summary,
                'comment_count' => $comments->count()
            ];

        } catch (\Exception $e) {
            Log::error('AI Summary Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to generate summary. Please try again later.'
            ];
        }
    }

    /**
     * Prepare discussion content for AI processing
     */
    private function prepareDiscussionContent(Discussion $discussion, $comments): string
    {
        $content = "Discussion Title: {$discussion->titre}\n";
        $content .= "Discussion Content: {$discussion->contenu}\n\n";
        $content .= "Comments:\n";

        foreach ($comments as $comment) {
            $content .= "User: {$comment->user->name}\n";
            $content .= "Comment: {$comment->contenu}\n";
            $content .= "Votes: {$comment->upvotes} upvotes, {$comment->downvotes} downvotes\n";
            $content .= "---\n";
        }

        return $content;
    }

    /**
     * Call Gemini API to generate summary
     */
    private function callGeminiApi(string $content): string
    {
        $prompt = "Summarize this book discussion in 3-5 sentences. Focus on main topics, key points, and overall sentiment.\n\nDiscussion:\n{$content}";

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
                'maxOutputTokens' => 1000,
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
        
        Log::info('Gemini API Request', [
            'url' => $url,
            'model' => $this->model,
            'content_length' => strlen($content)
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

            Log::info('Gemini API Response', [
                'status_code' => $response->getStatusCode(),
                'response_data' => $responseData,
                'candidates_count' => count($responseData['candidates'] ?? []),
                'first_candidate' => $responseData['candidates'][0] ?? null
            ]);

            // Check for different response structures
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                return trim($responseData['candidates'][0]['content']['parts'][0]['text']);
            }

            // Check for alternative response structure (gemini-2.5-flash)
            if (isset($responseData['candidates'][0]['content']['parts'])) {
                $parts = $responseData['candidates'][0]['content']['parts'];
                foreach ($parts as $part) {
                    if (isset($part['text'])) {
                        return trim($part['text']);
                    }
                }
            }

            // Check if response was truncated due to token limits
            if (isset($responseData['candidates'][0]['finishReason']) && 
                $responseData['candidates'][0]['finishReason'] === 'MAX_TOKENS') {
                // Try to get any partial content that might exist
                if (isset($responseData['candidates'][0]['content']['parts'])) {
                    $parts = $responseData['candidates'][0]['content']['parts'];
                    foreach ($parts as $part) {
                        if (isset($part['text']) && !empty(trim($part['text']))) {
                            return trim($part['text']) . "\n\n[Note: Response was truncated due to length]";
                        }
                    }
                }
                throw new \Exception('Response was truncated due to token limits. Please try with a shorter discussion.');
            }

            if (isset($responseData['error'])) {
                throw new \Exception('Gemini API Error: ' . $responseData['error']['message']);
            }

            throw new \Exception('Invalid response from Gemini API: ' . json_encode($responseData));
        } catch (RequestException $e) {
            Log::error('Gemini API Request Exception', [
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    /**
     * Check if content exceeds token limit (rough estimation)
     */
    private function exceedsTokenLimit(string $content): bool
    {
        // Rough estimation: 1 token ≈ 4 characters
        // For gemini-2.5-flash, let's be more conservative with input length
        $estimatedTokens = strlen($content) / 4;
        return $estimatedTokens > 100000; // Reduced from 800K to 100K for safety
    }

    /**
     * Truncate content to fit within token limits
     */
    private function truncateContent(string $content): string
    {
        // Keep the first 400K characters (roughly 100K tokens)
        $maxLength = 400000;
        
        if (strlen($content) <= $maxLength) {
            return $content;
        }

        $truncated = substr($content, 0, $maxLength);
        $truncated .= "\n\n[Content truncated due to length...]";
        
        return $truncated;
    }

    /**
     * Validate API configuration
     */
    public function validateConfiguration(): bool
    {
        return !empty($this->apiKey) && !empty($this->baseUrl) && !empty($this->model);
    }
}