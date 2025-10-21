<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favori extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'livre_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the favorite.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that is favorited.
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    /**
     * Scope to get favorites for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get favorites for a specific book.
     */
    public function scopeForBook($query, $bookId)
    {
        return $query->where('livre_id', $bookId);
    }

    /**
     * Check if a book is favorited by a user.
     */
    public static function isFavorited($userId, $bookId): bool
    {
        return self::where('user_id', $userId)
                   ->where('livre_id', $bookId)
                   ->exists();
    }

    /**
     * Toggle favorite status for a book.
     */
    public static function toggle($userId, $bookId): bool
    {
        $favorite = self::where('user_id', $userId)
                       ->where('livre_id', $bookId)
                       ->first();

        if ($favorite) {
            $favorite->delete();
            return false; // Removed from favorites
        } else {
            self::create([
                'user_id' => $userId,
                'livre_id' => $bookId,
            ]);
            return true; // Added to favorites
        }
    }
}