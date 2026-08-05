<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * @method Model getRecord()
 */
trait HasEnArTranslationForms
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        if (! method_exists($record, 'getTranslatedAttributes')) {
            return $data;
        }

        $record->loadMissing('translations');

        foreach (['en', 'ar'] as $locale) {
            $translation = $record->translation($locale);

            foreach ($record->getTranslatedAttributes() as $attribute) {
                $data[$locale][$attribute] = $translation?->getAttribute($attribute);
            }
        }

        foreach ($record->getTranslatedAttributes() as $attribute) {
            unset($data[$attribute]);
        }

        return $data;
    }
}
