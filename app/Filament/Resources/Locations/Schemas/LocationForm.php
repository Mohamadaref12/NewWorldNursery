<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('city')->required()->maxLength(255),
                TextInput::make('country')->maxLength(255),
                ColorPicker::make('badge_color')->default('#2E9E94'),
                Textarea::make('address')->rows(2),
                TextInput::make('phone')->tel()->maxLength(50),
                TextInput::make('email')->email()->maxLength(255),
                TextInput::make('working_hours')->maxLength(255)->placeholder('Sun – Thu: 7:00 AM – 6:00 PM'),
                TextInput::make('map_url')->url()->maxLength(500),
                TextInput::make('visit_url')->url()->maxLength(500)->label('Plan a Visit URL'),
                FileUpload::make('image')->image()->directory('locations')->disk('images')->visibility('public'),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->default(true),
            ]);
    }
}
