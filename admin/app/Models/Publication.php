<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'title', 'author', 'category', 'description',
        'title_en', 'description_en',
        'title_jp', 'description_jp',
        'source', 'link', 'sort_order',
    ];
}
