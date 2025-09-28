<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    protected $fillable = [
        'user_id',
        'livre_id',
        'note',
        'commentaire',
        'date_publication',
    ];

    protected $casts = [
        'date_publication' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($avis) {
            if (empty($avis->date_publication)) {
                $avis->date_publication = now()->toDateString();
            }
        });
    }

    /**
     * Get the user that owns the avis.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the livre that owns the avis.
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class, 'livre_id');
    }
}