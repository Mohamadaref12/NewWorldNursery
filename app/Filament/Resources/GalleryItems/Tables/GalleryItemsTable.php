<?php

namespace App\Filament\Resources\GalleryItems\Tables;

use App\Models\GalleryItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GalleryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->disk('images'),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        GalleryItem::TYPE_INSTAGRAM => 'Instagram',
                        GalleryItem::TYPE_MOMENTS => 'Moments',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        GalleryItem::TYPE_INSTAGRAM => 'info',
                        GalleryItem::TYPE_MOMENTS => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('alt'),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')->options([
                    GalleryItem::TYPE_MOMENTS => 'Moments of Joy',
                    GalleryItem::TYPE_INSTAGRAM => 'Instagram Feed',
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
