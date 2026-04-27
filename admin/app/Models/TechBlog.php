<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechBlog extends Model
{
    protected $table = 'tech_blogs';

    protected $fillable = [
        'title', 'author', 'category', 'description',
        'title_en', 'description_en',
        'title_jp', 'description_jp',
        'thumbnail', 'image_path', 'link', 'sort_order', 'is_active', 'published_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 이미지 URL (업로드 파일 우선, 없으면 thumbnail URL)
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return '/admin/' . $this->image_path;
        }
        return $this->thumbnail ?: null;
    }
}
