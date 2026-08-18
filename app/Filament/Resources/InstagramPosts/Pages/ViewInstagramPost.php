<?php

namespace App\Filament\Resources\InstagramPosts\Pages;

use App\Filament\Resources\InstagramPosts\InstagramPostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class ViewInstagramPost extends ViewRecord
{
    protected static string $resource = InstagramPostResource::class;

    protected Width | string | null $maxContentWidth = Width::SevenExtraLarge;

    public function getHeading(): string
    {
        return 'View post';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('openOnInstagram')
                ->label('Open on Instagram')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url(fn (): ?string => $this->record->permalink)
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->record->permalink)),
            DeleteAction::make(),
        ];
    }
}
