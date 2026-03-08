<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// Spatie Media Library
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'status',
        'published_at',
        'score'
    ];

    // İsteğe bağlı: Media collection
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    // Kategoriler ilişkisi
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Kullanıcı ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function calculateScore()
    {
        $comments = $this->comments()->count();

        $this->score =
            ($this->views * 0.1) +
            ($comments * 2) +
            ($this->reactions * 3);
        $this->save();
        return $this->score;
    }
}
