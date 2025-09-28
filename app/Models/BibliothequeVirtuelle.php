<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibliothequeVirtuelle extends Model
{
    // Each bibliotheque belongs to a user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom_bibliotheque',
        'nb_livres',
    ];

    /**
     * Get the user that owns the bibliotheque.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the livre utilisateurs (book instances) in this bibliotheque.
     */
    public function livreUtilisateurs(): HasMany
    {
        return $this->hasMany(LivreUtilisateur::class, 'bibliotheque_id');
    }
}
