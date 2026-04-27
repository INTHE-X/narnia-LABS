<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceMenu extends Model
{
    protected $table = 'resource_menus';

    protected $fillable = [
        'title', 'url', 'is_external', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'is_active'   => 'boolean',
    ];
}
