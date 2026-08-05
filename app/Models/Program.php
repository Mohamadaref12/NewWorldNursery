<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use App\Traits\InteractsWithEnArTranslations;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use InteractsWithEnArTranslations;
    use Translatable;

    public array $translatedAttributes = [
        'title',
        'age_range',
        'description',
    ];

    protected $fillable = [
        'color',
        'icon',
        'icon_color',
        'image',
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
        return ProgramTranslation::class;
    }

    public function getTitleAttribute(): ?string
    {
        return $this->getTranslatedAttribute('title');
    }

    public function getAgeRangeAttribute(): ?string
    {
        return $this->getTranslatedAttribute('age_range');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->getTranslatedAttribute('description');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedDisplayValue('title', 'Program #'.$this->getKey());
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }
}
