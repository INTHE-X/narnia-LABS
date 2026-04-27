<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'description_en',
        'description_jp',
        'image_path',
        'sort_order',
    ];

    // 이미지 public URL 반환
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        // public/uploads/teams/xxx.jpg → /admin/uploads/teams/xxx.jpg
        return '/admin/' . $this->image_path;
    }
}
