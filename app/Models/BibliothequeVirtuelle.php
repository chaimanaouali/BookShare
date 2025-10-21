<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BibliothequeVirtuelle extends Model
{
    use HasFactory, SoftDeletes;

    // Each bibliotheque belongs to a user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    protected $fillable = [
        'user_id',
        'nom_bibliotheque',
        'description',
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
     * Get the livres (books) in this bibliotheque.
     */
    public function livres(): HasMany
    {
        return $this->hasMany(Livre::class, 'bibliotheque_id');
    }

    /**
     * Get the livre utilisateurs (book instances) in this bibliotheque.
     * @deprecated Use livres() instead
     */
    public function livreUtilisateurs(): HasMany
    {
        return $this->hasMany(Livre::class, 'bibliotheque_id');
    }

    /**
     * Get the discussions for this bibliotheque.
     */
    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class, 'bibliotheque_id');
    }
}
