<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Filament\Forms\LocaleTabs;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                LocaleTabs::make(
                    [
                        TextInput::make('en.title')->label('Title')->required()->maxLength(255),
                        TextInput::make('en.age_range')->label('Age range')->maxLength(100),
                        Textarea::make('en.description')->label('Description')->rows(3),
                    ],
                    [
                        TextInput::make('ar.title')->label('العنوان')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('ar.age_range')->label('الفئة العمرية')->maxLength(100)->extraInputAttributes(['dir' => 'rtl']),
                        Textarea::make('ar.description')->label('الوصف')->rows(3)->extraInputAttributes(['dir' => 'rtl']),
                    ],
                ),
                Section::make('Details')->schema([
                    ColorPicker::make('color')->default('#F5E6A8')->label('Card background'),
                    TextInput::make('icon')->maxLength(50)->helperText('Emoji e.g. 👶'),
                    ColorPicker::make('icon_color')->default('#C8E6C9'),
                    FileUpload::make('image')->image()->directory('programs')->disk('images')->visibility('public'),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_active')->default(true),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
