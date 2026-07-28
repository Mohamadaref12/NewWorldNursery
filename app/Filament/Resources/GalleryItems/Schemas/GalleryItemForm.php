<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use App\Models\GalleryItem;
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
                Select::make('type')
                    ->options([
                        GalleryItem::TYPE_MOMENTS => 'Moments of Joy (Gallery)',
                        GalleryItem::TYPE_INSTAGRAM => 'Instagram Feed',
                    ])
                    ->default(GalleryItem::TYPE_MOMENTS)
                    ->required()
                    ->native(false),
                FileUpload::make('image')->image()->required()->directory('gallery')->disk('images')->visibility('public'),
                TextInput::make('alt')->maxLength(255),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->default(true),
            ]);
    }
}
