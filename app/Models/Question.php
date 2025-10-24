<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function hearts()
    {
        return $this->morphMany(Heart::class, 'heartable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // 🔹 Saber si el usuario actual ya dio "me gusta"
    public function isHearted()
    {
        if (!auth()->check()) return false;

        return $this->hearts()
            ->where('user_id', auth()->id())
            ->exists();
    }

    // Agregar o quitar corazones (me gusta)
public function heart()
{
    if (!auth()->check()) return;

    $this->hearts()->create([
        'user_id' => auth()->id(),
    ]);
}

public function unheart()
{
    if (!auth()->check()) return;

    $this->hearts()
        ->where('user_id', auth()->id())
        ->delete();
}

}
