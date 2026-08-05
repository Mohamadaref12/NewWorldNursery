<?php

namespace App\Filament\Resources\InstagramPosts\Schemas;

use App\Filament\Forms\LocaleTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InstagramPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post')->schema([
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
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_active')
                        ->default(true),
                ])->columns(2)->columnSpanFull(),
                LocaleTabs::make(
                    [
                        TextInput::make('en.alt')->label('Alt text')->maxLength(255),
                    ],
                    [
                        TextInput::make('ar.alt')->label('النص البديل')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                    ],
                ),
            ]);
    }
}
