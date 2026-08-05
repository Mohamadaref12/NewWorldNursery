<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use App\Traits\InteractsWithEnArTranslations;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use InteractsWithEnArTranslations;
    use Translatable;

    public array $translatedAttributes = [
        'name',
        'city',
        'country',
        'address',
        'working_hours',
    ];

    protected $fillable = [
        'badge_color',
        'phone',
        'email',
        'map_url',
        'visit_url',
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
        return LocationTranslation::class;
    }

    public function getNameAttribute(): ?string
    {
        return $this->getTranslatedAttribute('name');
    }

    public function getCityAttribute(): ?string
    {
        return $this->getTranslatedAttribute('city');
    }

    public function getCountryAttribute(): ?string
    {
        return $this->getTranslatedAttribute('country');
    }

    public function getAddressAttribute(): ?string
    {
        return $this->getTranslatedAttribute('address');
    }

    public function getWorkingHoursAttribute(): ?string
    {
        return $this->getTranslatedAttribute('working_hours');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedDisplayValue('name', 'Location #'.$this->getKey());
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }
}
