<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipationDefi extends Model
{
    protected $fillable = [
        'user_id',
        'defi_id',
        'livre_id',
        'status',
        'commentaire',
        'note',
        'quiz_score',
        'quiz_total_questions',
        'quiz_completed_at',
        'average_score',
        'completion_time_minutes',
        'ranking_score',
        'date_debut_lecture',
        'date_fin_lecture',
    ];

    protected $casts = [
        'date_debut_lecture' => 'datetime',
        'date_fin_lecture' => 'datetime',
        'quiz_completed_at' => 'datetime',
        'average_score' => 'decimal:2',
        'ranking_score' => 'decimal:2',
    ];

    /**
     * Get the user who participates in the défi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the défi that the user participates in.
     */
    public function defi(): BelongsTo
    {
        return $this->belongsTo(Defi::class);
    }

    /**
     * Get the book that the user is reading for this défi.
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    /**
     * Scope to get participations by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get participations by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get participations by défi
     */
    public function scopeByDefi($query, $defiId)
    {
        return $query->where('defi_id', $defiId);
    }

    /**
     * Calculate and update ranking scores for a participation
     */
    public function calculateRankingScores()
    {
        // Calculate average score (note + quiz score)
        $totalScore = 0;
        $scoreCount = 0;
        
        if ($this->note) {
            $totalScore += $this->note;
            $scoreCount++;
        }
        
        if ($this->quiz_score && $this->quiz_total_questions) {
            $quizPercentage = ($this->quiz_score / $this->quiz_total_questions) * 5; // Convert to 1-5 scale
            $totalScore += $quizPercentage;
            $scoreCount++;
        }
        
        $this->average_score = $scoreCount > 0 ? round($totalScore / $scoreCount, 2) : null;
        
        // Calculate completion time in minutes
        if ($this->date_debut_lecture && $this->date_fin_lecture) {
            $this->completion_time_minutes = $this->date_debut_lecture->diffInMinutes($this->date_fin_lecture);
        }
        
        // Calculate overall ranking score (weighted combination)
        $this->ranking_score = $this->calculateOverallRankingScore();
        
        $this->save();
    }

    /**
     * Calculate overall ranking score using weighted criteria
     */
    private function calculateOverallRankingScore()
    {
        $score = 0;
        $weight = 0;
        
        // Average score weight: 40%
        if ($this->average_score) {
            $score += $this->average_score * 0.4;
            $weight += 0.4;
        }
        
        // Completion speed weight: 30% (faster = higher score)
        if ($this->completion_time_minutes) {
            // Convert time to score (shorter time = higher score)
            // Assuming max time is 7 days (10080 minutes), normalize to 1-5 scale
            $maxTime = 10080; // 7 days in minutes
            $timeScore = max(1, 5 - (($this->completion_time_minutes / $maxTime) * 4));
            $score += $timeScore * 0.3;
            $weight += 0.3;
        }
        
        // Number of completed challenges weight: 30%
        $completedCount = static::where('user_id', $this->user_id)
            ->where('status', 'termine')
            ->count();
        
        // Normalize completed count to 1-5 scale (assuming max 10 challenges)
        $maxChallenges = 10;
        $completionScore = min(5, max(1, ($completedCount / $maxChallenges) * 5));
        $score += $completionScore * 0.3;
        $weight += 0.3;
        
        return $weight > 0 ? round($score / $weight, 2) : 0;
    }

    /**
     * Get user's ranking position for a specific défi
     */
    public function getRankingPosition()
    {
        $participations = static::where('defi_id', $this->defi_id)
            ->where('status', 'termine')
            ->orderByDesc('ranking_score')
            ->orderByDesc('average_score')
            ->orderBy('completion_time_minutes')
            ->get();
        
        $position = $participations->search(function ($participation) {
            return $participation->id === $this->id;
        });
        
        return $position !== false ? $position + 1 : null;
    }

    /**
     * Scope to get completed participations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'termine');
    }

    /**
     * Scope to order by ranking score
     */
    public function scopeByRanking($query)
    {
        return $query->orderByDesc('ranking_score')
            ->orderByDesc('average_score')
            ->orderBy('completion_time_minutes');
    }
}
