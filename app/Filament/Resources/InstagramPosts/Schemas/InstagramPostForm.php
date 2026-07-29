<?php

namespace App\Filament\Resources\InstagramPosts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class InstagramPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('instagram_media_id')
                    ->label('Instagram Media ID')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Required unique identifier for synced or manual Instagram items.'),
                FileUpload::make('image')
                    ->image()
                    ->required()
                    ->directory('instagram')
                    ->disk('images')
                    ->visibility('public'),
                TextInput::make('permalink')
                    ->url()
                    ->maxLength(255)
                    ->helperText('Optional link to the Instagram post.'),
                TextInput::make('alt')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
