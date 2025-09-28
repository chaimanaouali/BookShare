<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivreUtilisateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bibliotheque_id',
        'livre_id',
        'fichier_livre',
        'format',
        'taille',
        'visibilite',
        'description',
    ];

    public function bibliotheque()
    {
        return $this->belongsTo(BibliothequeVirtuelle::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class, 'livre_id');
    }
}
