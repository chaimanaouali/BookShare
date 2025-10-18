<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Defi;
use App\Models\ParticipationDefi;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Show ranking for a specific défi
     */
    public function show(Defi $defi)
    {
        // Get all completed participations for this défi
        $participations = ParticipationDefi::where('defi_id', $defi->id)
            ->where('status', 'termine')
            ->with(['user', 'livre'])
            ->get();

        // Calculate ranking scores for each participation
        foreach ($participations as $participation) {
            $participation->calculateRankingScores();
        }

        // Sort by ranking score
        $participations = $participations->sortByDesc('ranking_score');

        // Add ranking position
        $rankedParticipations = $participations->map(function ($participation, $index) {
            $participation->ranking_position = $index + 1;
            return $participation;
        });

        return view('admin.defis.ranking', compact('defi', 'rankedParticipations'));
    }

    /**
     * Show global ranking (all défis combined)
     */
    public function global()
    {
        // Get all users with their completed participations
        $users = \App\Models\User::whereHas('participationDefis', function ($query) {
            $query->where('status', 'termine');
        })->with(['participationDefis' => function ($query) {
            $query->where('status', 'termine');
        }])->get();

        $userRankings = [];

        foreach ($users as $user) {
            $completedParticipations = $user->participationDefis->where('status', 'termine');
            
            if ($completedParticipations->count() > 0) {
                // Calculate average score across all completed challenges
                $totalScore = 0;
                $totalTime = 0;
                $scoreCount = 0;
                
                foreach ($completedParticipations as $participation) {
                    $participation->calculateRankingScores();
                    
                    if ($participation->average_score) {
                        $totalScore += $participation->average_score;
                        $scoreCount++;
                    }
                    
                    if ($participation->completion_time_minutes) {
                        $totalTime += $participation->completion_time_minutes;
                    }
                }
                
                $averageScore = $scoreCount > 0 ? $totalScore / $scoreCount : 0;
                $averageTime = $completedParticipations->count() > 0 ? $totalTime / $completedParticipations->count() : 0;
                
                $userRankings[] = [
                    'user' => $user,
                    'completed_challenges' => $completedParticipations->count(),
                    'average_score' => round($averageScore, 2),
                    'average_time_minutes' => round($averageTime, 0),
                    'total_score' => round($averageScore * $completedParticipations->count(), 2)
                ];
            }
        }

        // Sort by total score (average score * number of completed challenges)
        usort($userRankings, function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Add ranking positions
        foreach ($userRankings as $index => &$ranking) {
            $ranking['position'] = $index + 1;
        }

        return view('admin.defis.global-ranking', compact('userRankings'));
    }

    /**
     * Recalculate all ranking scores
     */
    public function recalculate()
    {
        $participations = ParticipationDefi::where('status', 'termine')->get();
        
        foreach ($participations as $participation) {
            $participation->calculateRankingScores();
        }

        return redirect()->back()->with('success', 'Classements recalculés avec succès !');
    }
}