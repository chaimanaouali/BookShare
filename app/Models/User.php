<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /**
     * Role constants
     */
    public const ROLE_CONTRIBUTOR = 'contributor';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * Check if the user has the contributor role.
     */
    public function isContributor(): bool
    {
        return $this->role === self::ROLE_CONTRIBUTOR;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the avis for the user.
     */
    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
    }

    /**
     * Get the bibliotheques for the user.
     */
    public function bibliotheques(): HasMany
    {
        return $this->hasMany(BibliothequeVirtuelle::class);
    }

    /**
     * Get the livres (books) owned by the user.
     */
    public function livres(): HasMany
    {
        return $this->hasMany(Livre::class);
    }

    /**
     * Get the livre utilisateurs (book instances) for the user.
     * @deprecated Use livres() instead
     */
    public function livreUtilisateurs(): HasMany
    {
        return $this->hasMany(Livre::class);
    }

    /**
     * Get the recommendations for the user.
     */
    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    /**
     * Get the défi participations for the user.
     */
    public function participationDefis(): HasMany
    {
        return $this->hasMany(ParticipationDefi::class);
    }

    /**
     * Get the emprunts (borrowings) for the user.
     */
    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class, 'utilisateur_id');
    }

    /**
     * Get the reading personalities for the user.
     */
    public function readingPersonalities(): HasMany
    {
        return $this->hasMany(ReadingPersonality::class);
    }

    /**
     * Get the latest reading personality for the user.
     */
    public function latestReadingPersonality()
    {
        return $this->readingPersonalities()->latestForUser($this->id)->first();
    }
}
