<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recommendation extends Model
{
    protected $fillable = [
        'user_id',
        'livre_id',
        'avis_id',
        'score',
        'date_creation',
        'source',
        'reason',
        'is_viewed',
    ];

    protected $casts = [
        'date_creation' => 'date',
        'score' => 'float',
        'is_viewed' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recommendation) {
            $recommendation->date_creation = now()->toDateString();
        });
    }

    /**
     * Get the user that owns the recommendation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the livre that is being recommended.
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class, 'livre_id');
    }

    /**
     * Get the avis that this recommendation is based on.
     */
    public function avis(): BelongsTo
    {
        return $this->belongsTo(Avis::class, 'avis_id');
    }

    /**
     * Scope to get recommendations by source
     */
    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope to get AI recommendations
     */
    public function scopeAi($query)
    {
        return $query->where('source', 'AI');
    }

    /**
     * Scope to get collaborative recommendations
     */
    public function scopeCollaborative($query)
    {
        return $query->where('source', 'collaborative');
    }

    /**
     * Scope to get manual recommendations
     */
    public function scopeManual($query)
    {
        return $query->where('source', 'manual');
    }

    /**
     * Scope to get unviewed recommendations
     */
    public function scopeUnviewed($query)
    {
        return $query->where('is_viewed', false);
    }

    /**
     * Scope to get recommendations for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get recommendations by score range
     */
    public function scopeByScoreRange($query, $minScore, $maxScore = null)
    {
        $query->where('score', '>=', $minScore);
        if ($maxScore !== null) {
            $query->where('score', '<=', $maxScore);
        }
        return $query;
    }
}
