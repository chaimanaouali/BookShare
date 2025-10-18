<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends Model
{
    protected $fillable = [
        'titre',
        'contenu',
        'user_id',
        'bibliotheque_id',
        'est_resolu',
    ];

    protected $casts = [
        'est_resolu' => 'boolean',
    ];

    /**
     * Get the user that created this discussion.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bibliotheque that this discussion belongs to.
     */
    public function bibliotheque(): BelongsTo
    {
        return $this->belongsTo(BibliothequeVirtuelle::class, 'bibliotheque_id');
    }

    /**
     * Get the comments for this discussion.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the top-level comments for this discussion.
     */
    public function topLevelComments(): HasMany
    {
        return $this->hasMany(Comment::class)->topLevel();
    }

    /**
     * Scope to get resolved discussions
     */
    public function scopeResolved($query)
    {
        return $query->where('est_resolu', true);
    }

    /**
     * Scope to get unresolved discussions
     */
    public function scopeUnresolved($query)
    {
        return $query->where('est_resolu', false);
    }

    /**
     * Scope to get discussions by bibliotheque
     */
    public function scopeByBibliotheque($query, $bibliothequeId)
    {
        return $query->where('bibliotheque_id', $bibliothequeId);
    }

    /**
     * Scope to get discussions by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
