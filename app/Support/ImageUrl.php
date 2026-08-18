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

    /**
     * URL for a file in the Laravel public/ folder.
     * Production is served from the project root, so /public must be in the path.
     */
    public static function publicFile(string $path): string
    {
        $path = ltrim($path, '/');
        $base = rtrim((string) (config('app.asset_url') ?: config('app.url')), '/');

        if (str_ends_with($base, '/public')) {
            return $base.'/'.$path;
        }

        $host = (string) parse_url($base, PHP_URL_HOST);
        $isLocal = str_ends_with($host, '.test')
            || in_array($host, ['localhost', '127.0.0.1'], true);

        if (! $isLocal) {
            $base .= '/public';
        }

        return $base.'/'.$path;
    }
}
