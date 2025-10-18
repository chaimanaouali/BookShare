<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'contenu',
        'user_id',
        'discussion_id',
        'parent_id',
        'upvotes',
        'downvotes',
        'score',
    ];

    /**
     * Get the user that created this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the discussion that this comment belongs to.
     */
    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class);
    }

    /**
     * Get the parent comment (for nested replies).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Scope to get top-level comments (not replies).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get replies only.
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Get the votes for this comment.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(CommentVote::class);
    }

    /**
     * Get upvotes for this comment.
     */
    public function upvotes(): HasMany
    {
        return $this->hasMany(CommentVote::class)->where('vote_type', 'upvote');
    }

    /**
     * Get downvotes for this comment.
     */
    public function downvotes(): HasMany
    {
        return $this->hasMany(CommentVote::class)->where('vote_type', 'downvote');
    }

    /**
     * Check if a user has voted on this comment.
     */
    public function userVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    /**
     * Scope to sort by score (most popular first) computed from votes.
     */
    public function scopeByScore($query)
    {
        return $query
            ->withCount([
                'votes as upvote_count' => function ($q) { $q->where('vote_type', 'upvote'); },
                'votes as downvote_count' => function ($q) { $q->where('vote_type', 'downvote'); },
            ])
            ->orderByRaw('(upvote_count - downvote_count) DESC')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope to sort by newest first.
     */
    public function scopeByNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope to sort by most active (most comments + votes).
     */
    public function scopeByMostActive($query)
    {
        return $query->orderByRaw('(score + (SELECT COUNT(*) FROM comments WHERE parent_id = comments.id)) DESC');
    }
}
