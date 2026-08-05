<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property array<int, string> $translatedAttributes
 *
 * @mixin Model
 */
trait Translatable
{
    /** @var array<string, array<string, mixed>> */
    protected array $pendingTranslations = [];

    abstract public function translationModelClass(): string;

    public static function bootTranslatable(): void
    {
        static::saved(function (Model $model): void {
            /** @var self&Model $model */
            $model->persistPendingTranslations();
        });

        static::deleted(function (Model $model): void {
            /** @var self&Model $model */
            $model->translations()->delete();
        });
    }

    public function translations(): HasMany
    {
        return $this->hasMany($this->translationModelClass(), $this->translationForeignKey());
    }

    public function translationForeignKey(): string
    {
        return Str::snake(class_basename($this)).'_id';
    }

    public function getTranslatedAttributes(): array
    {
        return $this->translatedAttributes ?? [];
    }

    public function isTranslatedAttribute(string $key): bool
    {
        return in_array($key, $this->getTranslatedAttributes(), true);
    }

    public function getLocale(): string
    {
        return app()->getLocale();
    }

    public function getFallbackLocale(): string
    {
        return (string) config('app.fallback_locale', 'en');
    }

    public function translation(?string $locale = null): ?Model
    {
        $locale ??= $this->getLocale();

        if ($this->relationLoaded('translations')) {
            $match = $this->translations->firstWhere('locale', $locale);

            if ($match) {
                return $match;
            }
        }

        return $this->translations()->where('locale', $locale)->first();
    }

    public function getOrCreateTranslation(?string $locale = null): Model
    {
        $locale ??= $this->getLocale();

        $existing = $this->translation($locale);

        if ($existing) {
            return $existing;
        }

        return $this->translations()->make([
            'locale' => $locale,
        ]);
    }

    public function getTranslatedAttribute(string $key, ?string $locale = null, bool $withFallback = true): mixed
    {
        $locale ??= $this->getLocale();

        if (isset($this->pendingTranslations[$locale][$key])) {
            return $this->pendingTranslations[$locale][$key];
        }

        $translation = $this->translation($locale);

        if ($translation && filled($translation->getAttribute($key))) {
            return $translation->getAttribute($key);
        }

        if ($withFallback) {
            $fallback = $this->getFallbackLocale();

            if ($fallback !== $locale) {
                if (isset($this->pendingTranslations[$fallback][$key])) {
                    return $this->pendingTranslations[$fallback][$key];
                }

                $fallbackTranslation = $this->translation($fallback);

                if ($fallbackTranslation && filled($fallbackTranslation->getAttribute($key))) {
                    return $fallbackTranslation->getAttribute($key);
                }
            }
        }

        return null;
    }

    public function setTranslatedAttribute(string $key, mixed $value, ?string $locale = null): static
    {
        $locale ??= $this->getLocale();

        $this->pendingTranslations[$locale][$key] = $value;

        return $this;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getPendingTranslations(): array
    {
        return $this->pendingTranslations;
    }

    public function fill(array $attributes)
    {
        foreach (['en', 'ar'] as $locale) {
            if (! array_key_exists($locale, $attributes) || ! is_array($attributes[$locale])) {
                continue;
            }

            foreach ($attributes[$locale] as $key => $value) {
                if ($this->isTranslatedAttribute($key)) {
                    $this->setTranslatedAttribute($key, $value, $locale);
                }
            }

            unset($attributes[$locale]);
        }

        $translated = [];

        foreach ($attributes as $key => $value) {
            if ($this->isTranslatedAttribute($key)) {
                $translated[$key] = $value;
                unset($attributes[$key]);
            }
        }

        $result = parent::fill($attributes);

        foreach ($translated as $key => $value) {
            $this->setTranslatedAttribute($key, $value);
        }

        return $result;
    }

    public function setAttribute($key, $value)
    {
        if ($this->isTranslatedAttribute($key)) {
            return $this->setTranslatedAttribute($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        if ($this->isTranslatedAttribute($key) && ! array_key_exists($key, $this->attributes) && ! $this->hasGetMutator($key) && ! $this->hasAttributeMutator($key)) {
            return $this->getTranslatedAttribute($key);
        }

        return parent::getAttribute($key);
    }

    protected function persistPendingTranslations(): void
    {
        if ($this->pendingTranslations === []) {
            return;
        }

        foreach ($this->pendingTranslations as $locale => $attributes) {
            if (! collect($attributes)->contains(fn ($value) => filled($value))) {
                continue;
            }

            $translation = $this->translations()->firstOrNew(['locale' => $locale]);
            $translation->fill($attributes);
            $translation->{$this->translationForeignKey()} = $this->getKey();
            $translation->save();
        }

        $this->pendingTranslations = [];
        $this->unsetRelation('translations');
    }

    public function scopeWithTranslation(Builder $query, ?string $locale = null): Builder
    {
        $locale ??= app()->getLocale();
        $fallback = (string) config('app.fallback_locale', 'en');

        return $query->with(['translations' => function ($q) use ($locale, $fallback) {
            $q->whereIn('locale', array_unique([$locale, $fallback]));
        }]);
    }

    public function scopeWhereTranslation(Builder $query, string $attribute, mixed $value, ?string $locale = null): Builder
    {
        return $query->whereHas('translations', function (Builder $q) use ($attribute, $value, $locale) {
            $q->where($attribute, $value);

            if ($locale !== null) {
                $q->where('locale', $locale);
            }
        });
    }
}
