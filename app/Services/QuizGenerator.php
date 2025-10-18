<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class QuizGenerator
{
    public function fromText(string $text, int $numQuestions = 8, ?string $difficulty = null): array
    {
        $text = trim(Str::of($text)->squish());
        if ($text === '') {
            return [ 'title' => 'Quiz', 'questions' => [] ];
        }

        $schema = <<<'JSON'
{
  "title": "string",
  "questions": [
    {
      "id": "string",
      "type": "mcq|true_false|short_answer",
      "question": "string",
      "choices": ["string", "..."],
      "answer": "string|boolean",
      "explanation": "string",
      "difficulty": "easy|medium|hard"
    }
  ]
}
JSON;

        $rules = [
            'Return ONLY valid JSON (no markdown, no prose).',
            '60% MCQ, 20% True/False, 20% Short Answer.',
            'Every MCQ must include 3-5 choices with one correct answer.',
            'Keep questions concise and unambiguous.',
            'Add a one-sentence explanation for each answer.',
        ];

        $difficultyNote = $difficulty ? "Target overall difficulty: {$difficulty}." : '';
        $prompt = "Generate {$numQuestions} quiz questions from the SOURCE TEXT below.\n"
                . implode("\n", array_map(fn($r) => "- " . $r, $rules)) . "\n"
                . $difficultyNote . "\n\n"
                . "Schema (must match):\n{$schema}\n\n"
                . "SOURCE TEXT:\n{$text}\n";

        $baseUrl = config('services.groq.base_url');
        $apiKey = config('services.groq.api_key');
        $model = config('services.groq.model', 'llama-3.1-8b-instant');

        $response = Http::withToken($apiKey)
            ->baseUrl($baseUrl)
            ->post('/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a strict JSON generator for quizzes.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.4,
                'response_format' => [ 'type' => 'json_object' ],
            ])->throw()->json();

        $content = $response['choices'][0]['message']['content'] ?? '{}';
        $quiz = json_decode($content, true);
        if (!is_array($quiz)) {
            $quiz = [ 'title' => 'Quiz', 'questions' => [] ];
        }

        // Ensure IDs and minimal fields
        foreach (($quiz['questions'] ?? []) as $i => &$q) {
            if (empty($q['id'])) $q['id'] = (string) Str::uuid();
            if (empty($q['difficulty'])) $q['difficulty'] = $difficulty ? strtolower($difficulty) : 'medium';
        }
        unset($q);

        if (empty($quiz['title'])) {
            $quiz['title'] = 'Quiz généré';
        }

        return $quiz;
    }
}


