<?php

namespace App\Filament\Resources\GalleryCategories\Pages;

use App\Filament\Concerns\HasEnArTranslationForms;
use App\Filament\Resources\GalleryCategories\GalleryCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGalleryCategory extends EditRecord
{
    use HasEnArTranslationForms;

    protected static string $resource = GalleryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
