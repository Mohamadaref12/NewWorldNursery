<?php

namespace App\Filament\Resources\GalleryItems\Tables;

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
                TextColumn::make('category.name')->label('Category')->sortable(false)->searchable(false),
                TextColumn::make('alt')->searchable(query: function ($query, string $search): void {
                    $query->whereHas('translations', fn ($q) => $q->where('alt', 'like', "%{$search}%"));
                }),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('gallery_category_id')
                    ->label('Category')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn ($query) => $query->with('translations'),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
