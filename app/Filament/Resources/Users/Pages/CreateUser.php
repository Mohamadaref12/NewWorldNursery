<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected Width | string | null $maxContentWidth = Width::FiveExtraLarge;

    public function getHeading(): string
    {
        return 'Add user';
    }

    public function getSubheading(): ?string
    {
        return 'Create an account that can sign in to the admin panel.';
    }
}
