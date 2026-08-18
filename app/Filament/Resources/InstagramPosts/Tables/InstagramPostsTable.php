<?php

namespace App\Filament\Resources\InstagramPosts\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InstagramPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('images')
                    ->square()
                    ->imageSize(56)
                    ->label(''),
                TextColumn::make('alt')
                    ->label('Caption')
                    ->placeholder('No caption')
                    ->limit(50)
                    ->wrap()
                    ->searchable(query: fn ($query, string $search) => $query->whereHas(
                        'translations',
                        fn ($q) => $q->where('alt', 'like', "%{$search}%")
                    )),
                TextColumn::make('permalink')
                    ->label('Link')
                    ->placeholder('—')
                    ->limit(28)
                    ->url(fn ($record) => $record->permalink, shouldOpenInNewTab: true)
                    ->color('primary')
                    ->toggleable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Hidden')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Published'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                Action::make('open')
                    ->label('Instagram')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn ($record) => $record->permalink)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->permalink)),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->emptyStateHeading('No Instagram posts yet')
            ->emptyStateDescription('Sync from Instagram or add a post manually.')
            ->emptyStateIcon(Heroicon::OutlinedCamera);
    }
}
