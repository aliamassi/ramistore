<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $fillable = [
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
}
