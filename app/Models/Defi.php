<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Defi extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
    ];

    public function bookEvents(): HasMany
    {
        return $this->hasMany(BookEvent::class);
    }

    /**
     * Get the livres (books) associated with this défi.
     */
    public function livres(): HasMany
    {
        return $this->hasMany(Livre::class);
    }

    /**
     * Get the participations for this défi.
     */
    public function participations(): HasMany
    {
        return $this->hasMany(ParticipationDefi::class);
    }
}