<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Blogs\BlogResource;
use App\Models\Blog;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestBlogs extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest blogs')
            ->description('Most recently updated posts')
            ->query(fn (): Builder => Blog::query()->with('translations')->latest('updated_at'))
            ->columns([
                TextColumn::make('title')->limit(28),
                IconColumn::make('is_active')->boolean()->label('Live'),
                TextColumn::make('published_at')->date()->placeholder('Draft'),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Edit')
                    ->url(fn (Blog $record): string => BlogResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->headerActions([
                Action::make('all')
                    ->label('View all')
                    ->url(BlogResource::getUrl('index')),
            ]);
    }
}
