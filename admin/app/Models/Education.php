<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = [
        'title', 'category', 'description',
        'title_en', 'description_en',
        'title_jp', 'description_jp',
        'image_path', 'pdf_paths', 'link', 'published_at', 'sort_order', 'is_visible', 'is_aslanx', 'google_links',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'pdf_paths'    => 'array',
        'is_visible'   => 'boolean',
        'is_aslanx'    => 'boolean',
        'google_links' => 'array',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        return '/' . $this->image_path;
    }

    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('F j, Y') : '';
    }

    /**
     * PDF 파일 공개 URL 목록 반환
     * @return array  [['name' => '원본파일명.pdf', 'url' => '/admin/uploads/...'], ...]
     */
    public function getPdfFilesAttribute(): array
    {
        // 파일 첨부
        $files = array_map(function ($path) {
            $basename     = basename($path);
            $originalName = preg_replace('/^[a-f0-9\-]{36}__/', '', $basename);
            return [
                'type' => 'file',
                'name' => $originalName,
                'url'  => '/' . $path,
            ];
        }, $this->pdf_paths ?? []);

        // Google 링크
        $links = array_map(function ($link) {
            return [
                'type' => 'url',
                'name' => $link['name'] ?? 'Google 링크',
                'url'  => $link['url']  ?? '#',
            ];
        }, $this->google_links ?? []);

        return array_merge($files, $links);
    }

    /**
     * 신청자 관계
     */
    public function applicants()
    {
        return $this->hasMany(EducationApplicant::class, 'education_id');
    }
}
