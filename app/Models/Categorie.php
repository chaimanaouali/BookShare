<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Get the livres (books) in this category.
     */
    public function livres(): HasMany
    {
        return $this->hasMany(Livre::class, 'categorie_id');
    }

    /**
     * Scope to get categories with book count
     */
    public function scopeWithBookCount($query)
    {
        return $query->withCount('livres');
    }

    /**
     * Scope to get popular categories (with most books)
     */
    public function scopePopular($query)
    {
        return $query->withCount('livres')->orderBy('livres_count', 'desc');
    }
}
