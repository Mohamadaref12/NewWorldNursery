<?php

namespace App\Filament\Resources\Features\Schemas;

use App\Filament\Forms\LocaleTabs;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                LocaleTabs::make(
                    [
                        TextInput::make('en.title')->label('Title')->required()->maxLength(255),
                        Textarea::make('en.description')->label('Description')->rows(2),
                    ],
                    [
                        TextInput::make('ar.title')->label('العنوان')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                        Textarea::make('ar.description')->label('الوصف')->rows(2)->extraInputAttributes(['dir' => 'rtl']),
                    ],
                ),
                Section::make('Details')->schema([
                    ColorPicker::make('icon_color')->default('#D4EDDA'),
                    FileUpload::make('icon_image')->image()->directory('features')->disk('images')->visibility('public'),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_active')->default(true),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
