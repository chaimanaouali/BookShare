<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livre extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'cover_image',
        'publication_date',
        'genre',
        // New fields from LivreUtilisateur
        'user_id',
        'utilisateur_id',
        'bibliotheque_id',
        'fichier_livre',
        'format',
        'taille',
        'visibilite',
        'user_description',
        'langue',
        'nb_pages',
        'resume',
        'disponibilite',
        'etat',
        'categorie_id',
        'defi_id',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'disponibilite' => 'boolean',
    ];

    /**
     * Get the user that owns this book instance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bibliotheque that contains this book.
     */
    public function bibliotheque(): BelongsTo
    {
        return $this->belongsTo(BibliothequeVirtuelle::class, 'bibliotheque_id');
    }

    /**
     * Get the avis (reviews) for the livre.
     */
    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
    }

    /**
     * Get the emprunts (borrowings) for the livre.
     */
    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }

    /**
     * Get the category that this book belongs to.
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /**
     * Get the défi (challenge) that this book belongs to.
     */
    public function defi(): BelongsTo
    {
        return $this->belongsTo(Defi::class, 'defi_id');
    }

    /**
     * Scope to get books by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get books by bibliotheque
     */
    public function scopeByBibliotheque($query, $bibliothequeId)
    {
        return $query->where('bibliotheque_id', $bibliothequeId);
    }

    /**
     * Scope to get public books
     */
    public function scopePublic($query)
    {
        return $query->where('visibilite', 'public');
    }

    /**
     * Scope to get private books
     */
    public function scopePrivate($query)
    {
        return $query->where('visibilite', 'private');
    }

    /**
     * Scope to get available books
     */
    public function scopeAvailable($query)
    {
        return $query->where('disponibilite', true);
    }

    /**
     * Scope to get books by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('categorie_id', $categoryId);
    }
}
