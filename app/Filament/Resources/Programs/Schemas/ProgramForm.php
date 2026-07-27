<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('age_range')->maxLength(100),
                Textarea::make('description')->rows(3),
                ColorPicker::make('color')->default('#F5E6A8')->label('Card background'),
                TextInput::make('icon')->maxLength(50)->helperText('Emoji e.g. 👶'),
                ColorPicker::make('icon_color')->default('#C8E6C9'),
                FileUpload::make('image')->image()->directory('programs')->disk('public'),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->default(true),
            ]);
    }
}
