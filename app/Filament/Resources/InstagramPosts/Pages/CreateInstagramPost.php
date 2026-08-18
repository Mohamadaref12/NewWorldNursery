<?php

namespace App\Filament\Resources\InstagramPosts\Pages;

use App\Filament\Resources\InstagramPosts\InstagramPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInstagramPost extends CreateRecord
{
    protected static string $resource = InstagramPostResource::class;

    public function getTitle(): string
    {
        return 'Add Instagram post';
    }

    public function getHeading(): string
    {
        return 'Add Instagram post';
    }

    public function getSubheading(): ?string
    {
        return 'Upload an image and optional English / Arabic captions.';
    }
}
