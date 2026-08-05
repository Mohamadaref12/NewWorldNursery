<?php

namespace App\Models;

use App\Models\Concerns\Translatable;
use App\Traits\InteractsWithEnArTranslations;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use InteractsWithEnArTranslations;
    use Translatable;

    public array $translatedAttributes = [
        'title',
        'slug',
        'excerpt',
        'content',
    ];

    protected $fillable = [
        'image',
        'published_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Blog $blog): void {
            foreach ($blog->getPendingTranslations() as $locale => $attributes) {
                $title = $attributes['title'] ?? null;
                $slug = $attributes['slug'] ?? null;

                if (blank($slug) && filled($title)) {
                    $blog->setTranslatedAttribute('slug', Str::slug((string) $title), $locale);
                }
            }
        });
    }

    public function translationModelClass(): string
    {
        return BlogTranslation::class;
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

    public function getTitleAttribute(): ?string
    {
        return $this->getTranslatedAttribute('title');
    }

    public function getSlugAttribute(): ?string
    {
        return $this->getTranslatedAttribute('slug');
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->getTranslatedAttribute('excerpt');
    }

    public function getContentAttribute(): ?string
    {
        return $this->getTranslatedAttribute('content');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedDisplayValue('title', 'Blog #'.$this->getKey());
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }

    #[Scope]
    protected function latestPublished(Builder $query, int $limit = 5): void
    {
        $query->active()->limit($limit);
    }
}
