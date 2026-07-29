<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramSetting extends Model
{
    protected $fillable = [
        'app_id',
        'app_secret',
        'sync_limit',
    ];

    protected function casts(): array
    {
        return [
            'app_secret' => 'encrypted',
            'sync_limit' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'sync_limit' => (int) config('instagram.default_sync_limit', 12),
        ]);
    }

    public function resolvedAppId(): ?string
    {
        return filled($this->app_id) ? $this->app_id : config('instagram.app_id');
    }

    public function resolvedAppSecret(): ?string
    {
        return filled($this->app_secret) ? $this->app_secret : config('instagram.app_secret');
    }

    public function resolvedSyncLimit(): int
    {
        return max(1, min((int) ($this->sync_limit ?: config('instagram.default_sync_limit', 12)), 50));
    }

    public function isConfigured(): bool
    {
        return filled($this->resolvedAppId()) && filled($this->resolvedAppSecret());
    }

    public function redirectUri(): string
    {
        return (string) config('instagram.redirect_uri');
    }
}
