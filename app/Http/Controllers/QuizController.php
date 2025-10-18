<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuizGenerator;
use App\Models\ParticipationDefi;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function generate(Request $request, QuizGenerator $generator)
    {
        $data = $request->validate([
            'text' => 'required|string|min:50',
            'num_questions' => 'nullable|integer|min:3|max:30',
            'difficulty' => 'nullable|in:easy,medium,hard'
        ]);

        $quiz = $generator->fromText(
            $data['text'],
            $data['num_questions'] ?? 8,
            $data['difficulty'] ?? null
        );

        return response()->json($quiz);
    }

    /**
     * Generate a quiz from a participation's uploaded book file (PDF preferred).
     */
    public function generateFromParticipation(ParticipationDefi $participation, Request $request, QuizGenerator $generator)
    {
        $num = (int) $request->input('num_questions', 4);
        $difficulty = $request->input('difficulty');

        $livre = $participation->livre;
        if (!$livre) {
            return response()->json(['error' => 'Livre introuvable pour cette participation.'], 404);
        }

        // Build initial context from metadata
        $context = trim(($livre->title ? 'Titre: '.$livre->title.'. ' : '') .
                        ($livre->author ? 'Auteur: '.$livre->author.'. ' : '') .
                        ($livre->user_description ? 'Description: '.$livre->user_description : ''));

        $text = $context;

        // If a PDF is available, try to extract first ~3000 chars for focused quiz
        if ($livre->fichier_livre) {
            $path = storage_path('app/public/' . ltrim($livre->fichier_livre, '/'));
            if (is_file($path) && strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf') {
                try {
                    $parser = new PdfParser();
                    $pdf = $parser->parseFile($path);
                    $pages = $pdf->getPages();
                    $chunks = [];
                    foreach ($pages as $i => $page) {
                        if ($i >= 6) break; // limit pages to keep prompt small
                        $chunks[] = $page->getText();
                    }
                    $extracted = trim(implode("\n\n", $chunks));
                    if ($extracted) {
                        // Clean and truncate to ~3000 chars
                        $extracted = preg_replace('/[\x00-\x1F]+/u', ' ', $extracted);
                        $extracted = Str::of($extracted)->squish()->substr(0, 3000);
                        $text = $context . "\n\nContenu extrait (aperçu):\n" . $extracted;
                    }
                } catch (\Throwable $e) {
                    // Fallback to description if parsing fails
                }
            }
        }

        // Ensure minimum length for model
        if (Str::length($text) < 80) {
            $text = Str::padRight($text . ' ', 120, '-');
        }

        $quiz = $generator->fromText((string)$text, $num ?: 4, $difficulty ?: 'medium');

        return response()->json($quiz);
    }

    /**
     * Save quiz score for a participation
     */
    public function saveScore(ParticipationDefi $participation, Request $request)
    {
        $data = $request->validate([
            'score' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1'
        ]);

        $participation->update([
            'quiz_score' => $data['score'],
            'quiz_total_questions' => $data['total_questions'],
            'quiz_completed_at' => now()
        ]);

        // Recalculer les scores de classement
        $participation->calculateRankingScores();

        return response()->json([
            'success' => true,
            'message' => 'Score du quiz sauvegardé avec succès',
            'score' => $data['score'],
            'total' => $data['total_questions']
        ]);
    }
}

