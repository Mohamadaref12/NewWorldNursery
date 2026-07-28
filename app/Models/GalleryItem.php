<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    public const TYPE_INSTAGRAM = 'instagram';

    public const TYPE_MOMENTS = 'moments';

    protected $fillable = [
        'type',
        'image',
        'alt',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }

    #[Scope]
    protected function instagram(Builder $query): void
    {
        $query->where('type', self::TYPE_INSTAGRAM);
    }

    #[Scope]
    protected function moments(Builder $query): void
    {
        $query->where('type', self::TYPE_MOMENTS);
    }
}
