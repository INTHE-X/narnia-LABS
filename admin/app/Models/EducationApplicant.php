<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationApplicant extends Model
{
    protected $table = 'education_applicants';

    protected $fillable = [
        'education_id',
        'name',
        'email',
        'phone',
        'company',
        'jobtitle',
        'department',
    ];

    /**
     * 연결된 교육 콘텐츠
     */
    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id');
    }

    /**
     * 제출일 포맷 (Y.m.d)
     */
    public function getAppliedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('Y.m.d') : '-';
    }
}
