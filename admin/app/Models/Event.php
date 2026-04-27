<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'title_en',
        'description_en',
        'title_jp',
        'description_jp',
        'start_date',
        'end_date',
        'image_path',
        'link',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'is_featured' => 'boolean',
    ];

    // 이미지 public URL 반환
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        // public/uploads/events/xxx.jpg → /admin/uploads/events/xxx.jpg
        return '/admin/' . $this->image_path;
    }
}
