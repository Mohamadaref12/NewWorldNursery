<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageInstagram;
use App\Filament\Resources\Blogs\BlogResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\GalleryItems\GalleryItemResource;
use App\Filament\Resources\Locations\LocationResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\GalleryItem;
use App\Models\InstagramConnection;
use App\Models\InstagramPost;
use App\Models\Location;
use App\Models\Program;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NurseryStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'At a glance';

    protected function getStats(): array
    {
        $unreadMessages = ContactMessage::query()->where('is_read', false)->count();
        $messagesThisWeek = ContactMessage::query()->where('created_at', '>=', now()->startOfWeek())->count();
        $publishedBlogs = Blog::query()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();
        $activePrograms = Program::query()->where('is_active', true)->count();
        $activeLocations = Location::query()->where('is_active', true)->count();
        $galleryItems = GalleryItem::query()->where('is_active', true)->count();
        $instagramPosts = InstagramPost::query()->where('is_active', true)->count();
        $instagram = InstagramConnection::current();
        $instagramConnected = (bool) $instagram?->isConnected();

        $enquiryChart = collect(range(6, 0))
            ->map(fn (int $daysAgo): int => ContactMessage::query()
                ->whereDate('created_at', now()->subDays($daysAgo)->toDateString())
                ->count())
            ->all();

        return [
            Stat::make('Unread enquiries', $unreadMessages)
                ->description($messagesThisWeek.' this week')
                ->descriptionIcon(Heroicon::OutlinedEnvelope)
                ->color($unreadMessages > 0 ? 'danger' : 'success')
                ->chart($enquiryChart)
                ->chartColor($unreadMessages > 0 ? 'danger' : 'success')
                ->url(ContactMessageResource::getUrl('index')),
            Stat::make('Published blogs', $publishedBlogs)
                ->description('Visible on the website')
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->color('primary')
                ->url(BlogResource::getUrl('index')),
            Stat::make('Active programs', $activePrograms)
                ->description($activeLocations.' locations')
                ->descriptionIcon(Heroicon::OutlinedAcademicCap)
                ->color('warning')
                ->url(ProgramResource::getUrl('index')),
            Stat::make('Gallery images', $galleryItems)
                ->description($instagramPosts.' Instagram posts')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color('info')
                ->url(GalleryItemResource::getUrl('index')),
            Stat::make('Instagram', $instagramConnected ? '@'.($instagram->username ?? 'connected') : 'Not connected')
                ->description($instagramConnected
                    ? ($instagram->last_sync_status === 'success' ? 'Last sync OK' : ($instagram->last_sync_status ?: 'Connected'))
                    : 'Connect an account to sync posts')
                ->descriptionIcon(Heroicon::OutlinedCamera)
                ->color($instagramConnected ? 'success' : 'gray')
                ->url(ManageInstagram::getUrl()),
            Stat::make('Locations', $activeLocations)
                ->description('Nursery branches')
                ->descriptionIcon(Heroicon::OutlinedMapPin)
                ->color('success')
                ->url(LocationResource::getUrl('index')),
        ];
    }
}
