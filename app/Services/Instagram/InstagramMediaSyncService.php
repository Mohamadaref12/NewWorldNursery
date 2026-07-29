<?php

namespace App\Services\Instagram;

use App\Models\InstagramConnection;
use App\Models\InstagramPost;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class InstagramMediaSyncService
{
    /**
     * Sync recent Instagram IMAGE posts into instagram_posts.
     *
     * @return array{imported: int, updated: int, skipped: int}
     */
    public function sync(?InstagramConnection $connection = null): array
    {
        $connection ??= InstagramConnection::current();

        if (! $connection?->isConnected()) {
            throw new RuntimeException('Instagram is not connected.');
        }

        try {
            $media = $this->fetchMedia($connection);
            $stats = ['imported' => 0, 'updated' => 0, 'skipped' => 0];
            $order = 0;

            foreach ($media as $item) {
                $mediaType = $item['media_type'] ?? null;

                if ($mediaType === 'VIDEO') {
                    $stats['skipped']++;

                    continue;
                }

                $imageUrl = $this->resolveImageUrl($item);

                if (blank($imageUrl)) {
                    $stats['skipped']++;

                    continue;
                }

                $existing = InstagramPost::query()
                    ->where('instagram_media_id', $item['id'])
                    ->first();

                $path = $this->downloadImage($imageUrl, (string) $item['id'], $existing?->image);
                $alt = $this->excerptCaption($item['caption'] ?? null);
                $permalink = $item['permalink'] ?? null;

                if ($existing) {
                    $existing->update([
                        'image' => $path,
                        'alt' => $alt,
                        'permalink' => $permalink,
                        'sort_order' => $order,
                        'is_active' => true,
                    ]);
                    $stats['updated']++;
                } else {
                    InstagramPost::query()->create([
                        'instagram_media_id' => (string) $item['id'],
                        'image' => $path,
                        'alt' => $alt,
                        'permalink' => $permalink,
                        'sort_order' => $order,
                        'is_active' => true,
                    ]);
                    $stats['imported']++;
                }

                $order++;
            }

            $message = sprintf(
                'Imported %d, updated %d, skipped %d.',
                $stats['imported'],
                $stats['updated'],
                $stats['skipped']
            );
            $connection->markSyncSuccess($message);

            return $stats;
        } catch (Throwable $e) {
            $connection->markSyncFailed($e->getMessage());

            throw $e;
        }
    }

    /**
     * @return list<array<string, mixed>>
     *
     * @throws RequestException
     */
    protected function fetchMedia(InstagramConnection $connection): array
    {
        $limit = max(1, min((int) $connection->sync_limit, 50));

        $response = Http::get(
            'https://graph.facebook.com/'.config('instagram.graph_version').'/'.$connection->instagram_user_id.'/media',
            [
                'fields' => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp,children{media_type,media_url,thumbnail_url}',
                'limit' => $limit,
                'access_token' => $connection->access_token,
            ]
        )->throw();

        return $response->json('data', []);
    }

    /**
     * @param  array<string, mixed>  $item
     */
    protected function resolveImageUrl(array $item): ?string
    {
        $type = $item['media_type'] ?? null;

        if ($type === 'IMAGE') {
            return $item['media_url'] ?? null;
        }

        if ($type === 'CAROUSEL_ALBUM') {
            foreach ($item['children']['data'] ?? [] as $child) {
                if (($child['media_type'] ?? null) === 'IMAGE' && filled($child['media_url'] ?? null)) {
                    return $child['media_url'];
                }
            }

            return $item['media_url'] ?? $item['thumbnail_url'] ?? null;
        }

        return $item['thumbnail_url'] ?? $item['media_url'] ?? null;
    }

    protected function downloadImage(string $url, string $mediaId, ?string $existingPath = null): string
    {
        $contents = Http::timeout(60)->get($url)->throw()->body();

        $extension = $this->guessExtension($url, $contents);
        $path = 'instagram/'.$mediaId.'.'.$extension;

        if ($existingPath && $existingPath !== $path && Storage::disk('images')->exists($existingPath)) {
            Storage::disk('images')->delete($existingPath);
        }

        Storage::disk('images')->put($path, $contents);

        return $path;
    }

    protected function guessExtension(string $url, string $contents): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            return $ext === 'jpeg' ? 'jpg' : $ext;
        }

        if (str_starts_with($contents, "\x89PNG")) {
            return 'png';
        }

        if (str_starts_with($contents, 'GIF')) {
            return 'gif';
        }

        return 'jpg';
    }

    protected function excerptCaption(?string $caption): ?string
    {
        if (blank($caption)) {
            return null;
        }

        return Str::limit(trim(preg_replace('/\s+/', ' ', $caption) ?? $caption), 250);
    }
}
