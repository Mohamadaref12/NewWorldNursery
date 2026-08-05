<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use App\Traits\InteractsWithEnArTranslations;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstagramPost extends Model
{
    use InteractsWithEnArTranslations;
    use Translatable;

    public array $translatedAttributes = [
        'alt',
    ];

    protected $fillable = [
        'instagram_media_id',
        'image',
        'permalink',
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

    public function translationModelClass(): string
    {
        return InstagramPostTranslation::class;
    }

    public function getAltAttribute(): ?string
    {
        return $this->getTranslatedAttribute('alt');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedDisplayValue('alt', 'Instagram post #'.$this->getKey());
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }
}
