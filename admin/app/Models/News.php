<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title', 'source', 'category', 'content',
        'title_en', 'content_en',
        'title_jp', 'content_jp',
        'image_path', 'link', 'is_featured', 'published_at', 'sort_order',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        return '/admin/' . $this->image_path;
    }

    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('F j, Y') : '';
    }

    public function getYearAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('Y') : '';
    }
}
