<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /** -----------------------------
     * Relaciones con otras tablas
     * ----------------------------- */

    // Relación con preguntas (un usuario tiene muchas)
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Relación con respuestas (un usuario tiene muchas)
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // Relación con comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relación con corazones (likes)
    public function hearts()
    {
        return $this->hasMany(Heart::class);
    }
}
