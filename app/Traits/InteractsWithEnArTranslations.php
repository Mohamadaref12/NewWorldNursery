<?php

namespace App\Traits;

trait InteractsWithEnArTranslations
{
    /**
     * Prefer English, then Arabic, then app/fallback locales for admin labels.
     */
    public function localizedDisplayValue(string $attribute, string $fallback = ''): string
    {
        foreach (['en', 'ar', app()->getLocale(), config('app.fallback_locale', 'en')] as $locale) {
            $value = $this->getTranslatedAttribute($attribute, (string) $locale, false);

            if (filled($value)) {
                return (string) $value;
            }
        }

        return $fallback;
    }

    public function translationValue(string $attribute, string $locale): mixed
    {
        return $this->getTranslatedAttribute($attribute, $locale, false);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function setTranslationForLocale(string $locale, array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            if ($this->isTranslatedAttribute($key)) {
                $this->setTranslatedAttribute($key, $value, $locale);
            }
        }

        return $this;
    }
}
