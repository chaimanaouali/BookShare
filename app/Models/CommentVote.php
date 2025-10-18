<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentVote extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
        'vote_type',
    ];

    /**
     * Get the user that cast this vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comment that was voted on.
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}