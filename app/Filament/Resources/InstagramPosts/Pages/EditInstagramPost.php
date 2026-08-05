<?php

namespace App\Filament\Resources\InstagramPosts\Pages;

use App\Filament\Concerns\HasEnArTranslationForms;
use App\Filament\Resources\InstagramPosts\InstagramPostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstagramPost extends EditRecord
{
    use HasEnArTranslationForms;

    protected static string $resource = InstagramPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
