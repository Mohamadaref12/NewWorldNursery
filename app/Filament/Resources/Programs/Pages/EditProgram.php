<?php

namespace App\Filament\Resources\Programs\Pages;

use App\Filament\Concerns\HasEnArTranslationForms;
use App\Filament\Resources\Programs\ProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProgram extends EditRecord
{
    use HasEnArTranslationForms;

    protected static string $resource = ProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
