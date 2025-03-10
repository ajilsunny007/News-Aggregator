<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'source',
        'author',
        'published_at',
        'url',
        'category',
        'external_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_saved_articles');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($article) {
            event(new \App\Events\ArticleCreated($article));
        });
    }
}