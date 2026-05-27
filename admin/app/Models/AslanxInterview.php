<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AslanxInterview extends Model
{
    protected $table = 'aslanx_interviews';

    protected $fillable = [
        'company', 'company_en', 'company_jp',
        'position', 'position_en', 'position_jp',
        'content', 'content_en', 'content_jp',
        'logo_path',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];
}
