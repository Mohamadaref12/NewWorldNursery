<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use App\Traits\InteractsWithEnArTranslations;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GalleryCategory extends Model
{
    use InteractsWithEnArTranslations;
    use Translatable;

    public array $translatedAttributes = [
        'name',
        'slug',
    ];

    protected $fillable = [
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

    protected static function booted(): void
    {
        static::saving(function (GalleryCategory $category): void {
            foreach ($category->getPendingTranslations() as $locale => $attributes) {
                $name = $attributes['name'] ?? null;
                $slug = $attributes['slug'] ?? null;

                if (blank($slug) && filled($name)) {
                    $category->setTranslatedAttribute('slug', Str::slug((string) $name), $locale);
                }
            }
        });
    }

    public function translationModelClass(): string
    {
        return GalleryCategoryTranslation::class;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBindingQuery(static::query(), $value, $field)->firstOrFail();
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        if ($this->isTranslatedAttribute($field)) {
            return $query->whereHas('translations', fn ($q) => $q->where($field, $value));
        }

        return parent::resolveRouteBindingQuery($query, $value, $field);
    }

    public function getNameAttribute(): ?string
    {
        return $this->getTranslatedAttribute('name');
    }

    public function getSlugAttribute(): ?string
    {
        return $this->getTranslatedAttribute('slug');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedDisplayValue('name', 'Category #'.$this->getKey());
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }
}
