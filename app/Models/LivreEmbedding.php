<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivreEmbedding extends Model
{
    protected $fillable = [
        'livre_id',
        'embedding',
        'dimension',
    ];

    protected $casts = [
        'embedding' => 'array',
    ];

    /**
     * Get the livre that owns this embedding.
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }
}