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
     * Get the avis for the livre.
     */
    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
    }
}
