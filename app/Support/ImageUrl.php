<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class ImageUrl
{
    public static function make(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Storage::disk('images')->url($path);
    }
}
