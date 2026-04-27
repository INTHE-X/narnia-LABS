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
        'image_path', 'pdf_paths', 'link', 'published_at', 'sort_order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'pdf_paths'    => 'array',   // JSON → PHP array 자동 변환
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

    /**
     * PDF 파일 공개 URL 목록 반환
     * @return array  [['name' => '원본파일명.pdf', 'url' => '/admin/uploads/...'], ...]
     */
    public function getPdfFilesAttribute(): array
    {
        $paths = $this->pdf_paths ?? [];
        return array_map(function ($path) {
            // path 예: uploads/education_pdfs/uuid__filename.pdf
            $basename = basename($path);
            // uuid__ 접두사 제거해서 원본 파일명 복원
            $originalName = preg_replace('/^[a-f0-9\-]{36}__/', '', $basename);
            return [
                'name' => $originalName,
                'url'  => '/admin/' . $path,
            ];
        }, $paths);
    }

    /**
     * 신청자 관계
     */
    public function applicants()
    {
        return $this->hasMany(EducationApplicant::class, 'education_id');
    }
}
