<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected Width | string | null $maxContentWidth = Width::FiveExtraLarge;

    public function getHeading(): string
    {
        return 'Edit user';
    }

    public function getSubheading(): ?string
    {
        return $this->record->email;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn (): bool => $this->record->is(auth()->user())),
        ];
    }
}
