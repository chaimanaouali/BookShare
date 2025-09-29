<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueEmprunt extends Model
{
    protected $fillable = [
        'emprunt_id',
        'utilisateur_id',
        'action',
        'date_action',
        'details',
    ];

    protected $casts = [
        'date_action' => 'date',
    ];

    /**
     * Get the emprunt that owns the historique emprunt.
     */
    public function emprunt(): BelongsTo
    {
        return $this->belongsTo(Emprunt::class);
    }

    /**
     * Get the utilisateur that owns the historique emprunt.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
