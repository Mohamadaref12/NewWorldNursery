<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use App\Filament\Forms\LocaleTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item')->schema([
                    Select::make('gallery_category_id')
                        ->label('Category')
                        ->relationship(
                            name: 'category',
                            titleAttribute: 'id',
                            modifyQueryUsing: fn ($query) => $query->with('translations'),
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
                        ->searchable()
                        ->preload()
                        ->required()
                        ->native(false)
                        ->createOptionForm([
                            TextInput::make('en.name')->label('Name (EN)')->required()->maxLength(255),
                            TextInput::make('en.slug')->label('Slug (EN)')->maxLength(255)->helperText('Leave empty to auto-generate'),
                            TextInput::make('ar.name')->label('الاسم (AR)')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                            TextInput::make('ar.slug')->label('الرابط (AR)')->maxLength(255)->extraInputAttributes(['dir' => 'rtl']),
                            TextInput::make('sort_order')->numeric()->default(0),
                            Toggle::make('is_active')->default(true),
                        ]),
                    FileUpload::make('image')->image()->required()->directory('gallery')->disk('images')->visibility('public'),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_active')->default(true),
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
