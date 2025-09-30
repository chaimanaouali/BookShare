<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'cover_image',
        'publication_date',
        'genre',
    ];

    protected $casts = [
        'publication_date' => 'date',
    ];

    /**
     * Get the emprunts for the livre.
     * Get the avis for the livre.
     */
    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
    }

    /**
     * Get the livre utilisateurs (book instances) for this livre.
     */
    public function emprunts(): HasMany
    {
      return $this->hasMany(Emprunt::class);
    }
    public function livreUtilisateurs(): HasMany
    {

        return $this->hasMany(LivreUtilisateur::class);
    }
}
