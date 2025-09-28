<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    protected $fillable = [
        'title',
    ];

    /**
     * Get the emprunts for the livre.
     */
    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }
}
