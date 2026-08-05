<?php

namespace App\Filament\Resources\Locations\Schemas;

use App\Filament\Forms\LocaleTabs;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                LocaleTabs::make(
                    [
                        TextInput::make('en.name')->label('Name')->required()->maxLength(255),
                        TextInput::make('en.city')->label('City')->required()->maxLength(255),
                        TextInput::make('en.country')->label('Country')->maxLength(255),
                        Textarea::make('en.address')->label('Address')->rows(2),
                        TextInput::make('en.working_hours')->label('Working hours')->maxLength(255)
                            ->placeholder('Sun – Thu: 7:00 AM – 6:00 PM'),
                    ],
                    [
                        TextInput::make('ar.name')->label('الاسم')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('ar.city')->label('المدينة')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('ar.country')->label('الدولة')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                        Textarea::make('ar.address')->label('العنوان')->rows(2)->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('ar.working_hours')->label('ساعات العمل')->maxLength(255)
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->placeholder('الأحد – الخميس: ٧:٠٠ ص – ٦:٠٠ م'),
                    ],
                ),
                Section::make('Details')->schema([
                    ColorPicker::make('badge_color')->default('#2E9E94'),
                    TextInput::make('phone')->tel()->maxLength(50),
                    TextInput::make('email')->email()->maxLength(255),
                    TextInput::make('map_url')->url()->maxLength(500),
                    TextInput::make('visit_url')->url()->maxLength(500)->label('Plan a Visit URL'),
                    FileUpload::make('image')->image()->directory('locations')->disk('images')->visibility('public'),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_active')->default(true),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
