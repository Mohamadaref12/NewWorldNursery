<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gallery_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false)
                    ->createOptionForm([
                        TextInput::make('name')->required()->maxLength(255),
                        TextInput::make('slug')->maxLength(255)->helperText('Leave empty to auto-generate'),
                        TextInput::make('sort_order')->numeric()->default(0),
                        Toggle::make('is_active')->default(true),
                    ]),
                FileUpload::make('image')->image()->required()->directory('gallery')->disk('images')->visibility('public'),
                TextInput::make('alt')->maxLength(255),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->default(true),
            ]);
    }
}
