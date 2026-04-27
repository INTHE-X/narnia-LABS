<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    protected $table = 'case_studies';

    protected $fillable = [
        'title', 'category', 'tags', 'description',
        'title_en', 'description_en',
        'title_jp', 'description_jp',
        'image_path', 'link', 'is_featured', 'sort_order', 'is_published',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        return '/admin/' . $this->image_path;
    }

    // "#Generator,#Evaluator" → ['#Generator', '#Evaluator']
    public function getTagsArrayAttribute(): array
    {
        if (!$this->tags) return [];
        return array_filter(array_map('trim', explode(',', $this->tags)));
    }
}
