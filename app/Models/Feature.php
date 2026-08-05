<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use App\Traits\InteractsWithEnArTranslations;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use InteractsWithEnArTranslations;
    use Translatable;

    public array $translatedAttributes = [
        'title',
        'description',
    ];

    protected $fillable = [
        'icon_color',
        'icon_image',
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
        return FeatureTranslation::class;
    }

    public function getTitleAttribute(): ?string
    {
        return $this->getTranslatedAttribute('title');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->getTranslatedAttribute('description');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedDisplayValue('title', 'Feature #'.$this->getKey());
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }
}
