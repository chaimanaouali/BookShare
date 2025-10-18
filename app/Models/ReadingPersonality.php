<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingPersonality extends Model
{
    protected $fillable = [
        'user_id',
        'personality_title',
        'personality_description',
        'reading_patterns',
        'recommendations',
        'challenge_suggestion',
        'books_analyzed',
        'last_updated',
    ];

    protected $casts = [
        'reading_patterns' => 'array',
        'recommendations' => 'array',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the user that owns the reading personality.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get the latest personality for a user
     */
    public function scopeLatestForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->orderBy('last_updated', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Check if the personality needs updating (older than 30 days)
     */
    public function needsUpdate(): bool
    {
        if (!$this->last_updated) {
            return true;
        }
        
        return $this->last_updated->diffInDays(now()) > 30;
    }
}
