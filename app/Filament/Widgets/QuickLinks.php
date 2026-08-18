<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageInstagram;
use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\Blogs\BlogResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\GalleryItems\GalleryItemResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Models\ContactMessage;
use App\Models\InstagramConnection;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuickLinks extends StatsOverviewWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Quick links';

    protected ?string $description = 'Jump to the pages you use most.';

    /**
     * @var int | array<string, ?int> | null
     */
    protected int | array | null $columns = [
        'default' => 1,
        'sm' => 2,
        'xl' => 3,
    ];

    protected function getStats(): array
    {
        $unread = ContactMessage::query()->where('is_read', false)->count();
        $instagram = InstagramConnection::current();
        $instagramConnected = (bool) $instagram?->isConnected();

        return [
            Stat::make('Site settings', 'Configure')
                ->description('Hero, about, and translations')
                ->icon(Heroicon::OutlinedCog6Tooth)
                ->color('primary')
                ->url(ManageSiteSettings::getUrl()),
            Stat::make('Contact messages', $unread > 0 ? $unread.' unread' : 'Inbox')
                ->description('Read and reply to enquiries')
                ->icon(Heroicon::OutlinedEnvelope)
                ->color($unread > 0 ? 'danger' : 'info')
                ->url(ContactMessageResource::getUrl('index')),
            Stat::make('Blogs', 'Write')
                ->description('English and Arabic posts')
                ->icon(Heroicon::OutlinedNewspaper)
                ->color('warning')
                ->url(BlogResource::getUrl('index')),
            Stat::make('Programs', 'Manage')
                ->description('Age groups and descriptions')
                ->icon(Heroicon::OutlinedAcademicCap)
                ->color('success')
                ->url(ProgramResource::getUrl('index')),
            Stat::make('Gallery', 'Browse')
                ->description('Moments of Joy images')
                ->icon(Heroicon::OutlinedPhoto)
                ->color('info')
                ->url(GalleryItemResource::getUrl('index')),
            Stat::make('Instagram', $instagramConnected ? 'Connected' : 'Offline')
                ->description($instagramConnected
                    ? 'Sync and manage the feed'
                    : 'Connect an account to sync')
                ->icon(Heroicon::OutlinedCamera)
                ->color($instagramConnected ? 'success' : 'gray')
                ->url(ManageInstagram::getUrl()),
        ];
    }
}
