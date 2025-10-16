<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Emprunt extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'livre_id',
        'date_emprunt',
        'date_retour_prev',
        'date_retour_eff',
        'statut',
        'penalite',
        'commentaire',
    ];

    protected $casts = [
        'date_emprunt' => 'date',
        'date_retour_prev' => 'date',
        'date_retour_eff' => 'date',
        'penalite' => 'decimal:2',
    ];

    /**
     * Get the utilisateur that owns the emprunt.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Get the livre that owns the emprunt.
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    /**
     * Get the historique emprunts for the emprunt.
     */
    public function historiqueEmprunts(): HasMany
    {
        return $this->hasMany(HistoriqueEmprunt::class);
    }
}
