<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'start_date',
        'end_date',
        'status',
        'position',
        'show_dim',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'show_dim'   => 'boolean',
    ];

    public function isActive(): bool
    {
        if ($this->status !== 'active') return false;

        $today = now()->toDateString();
        if ($this->start_date && $this->start_date->toDateString() > $today) return false;
        if ($this->end_date   && $this->end_date->toDateString()   < $today) return false;

        return true;
    }
}
