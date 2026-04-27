<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $table = 'seo_settings';

    protected $fillable = [
        'page_key', 'page_name', 'page_url',
        'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image',
        'twitter_card', 'index_allow', 'canonical_url',
        'head_script',
    ];

    protected $casts = [
        'index_allow' => 'boolean',
    ];

    // SEO 점수 계산 (0~100)
    public function getSeoScoreAttribute(): int
    {
        $score = 0;
        if ($this->meta_title) {
            $len = mb_strlen($this->meta_title);
            $score += ($len >= 10 && $len <= 60) ? 25 : 10;
        }
        if ($this->meta_description) {
            $len = mb_strlen($this->meta_description);
            $score += ($len >= 50 && $len <= 160) ? 25 : 10;
        }
        if ($this->meta_keywords) $score += 15;
        if ($this->og_title)      $score += 15;
        if ($this->og_image)      $score += 20;
        return min($score, 100);
    }

    public function getScoreLabelAttribute(): string
    {
        $score = $this->seo_score;
        if ($score >= 80) return 'good';
        if ($score >= 50) return 'warning';
        return 'poor';
    }
}
