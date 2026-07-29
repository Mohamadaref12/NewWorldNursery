<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramConnection extends Model
{
    protected $fillable = [
        'instagram_user_id',
        'username',
        'page_id',
        'page_name',
        'access_token',
        'token_expires_at',
        'sync_limit',
        'last_synced_at',
        'last_sync_status',
        'last_sync_message',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'token_expires_at' => 'datetime',
            'last_synced_at' => 'datetime',
            'sync_limit' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public static function current(): ?self
    {
        return static::query()->where('is_active', true)->latest('id')->first();
    }

    public function isConnected(): bool
    {
        return filled($this->access_token) && filled($this->instagram_user_id);
    }

    public function markSyncSuccess(string $message): void
    {
        $this->update([
            'last_synced_at' => now(),
            'last_sync_status' => 'success',
            'last_sync_message' => $message,
        ]);
    }

    public function markSyncFailed(string $message): void
    {
        $this->update([
            'last_synced_at' => now(),
            'last_sync_status' => 'failed',
            'last_sync_message' => $message,
        ]);
    }
}
