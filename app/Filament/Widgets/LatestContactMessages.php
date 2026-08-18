<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestContactMessages extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest enquiries')
            ->description('Newest contact form messages')
            ->query(fn (): Builder => ContactMessage::query()->latest())
            ->columns([
                TextColumn::make('name')->searchable()->limit(20),
                TextColumn::make('program')->limit(16)->placeholder('—'),
                IconColumn::make('is_read')->boolean()->label('Read'),
                TextColumn::make('created_at')->since()->label('When'),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('View')
                    ->url(fn (ContactMessage $record): string => ContactMessageResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->headerActions([
                Action::make('all')
                    ->label('View all')
                    ->url(ContactMessageResource::getUrl('index')),
            ]);
    }
}
