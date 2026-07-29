<?php

namespace App\Console\Commands;

use App\Services\Instagram\InstagramMediaSyncService;
use Illuminate\Console\Command;
use Throwable;

class SyncInstagramMediaCommand extends Command
{
    protected $signature = 'instagram:sync';

    protected $description = 'Sync latest Instagram posts into instagram_posts';

    public function handle(InstagramMediaSyncService $sync): int
    {
        try {
            $stats = $sync->sync();

            $this->info(sprintf(
                'Instagram sync complete: imported %d, updated %d, skipped %d.',
                $stats['imported'],
                $stats['updated'],
                $stats['skipped']
            ));

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Instagram sync failed: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
