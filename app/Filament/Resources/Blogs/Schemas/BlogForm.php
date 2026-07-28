<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state, string $operation): void {
                        if ($operation === 'create') {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Used in the API URL'),
                Textarea::make('excerpt')
                    ->rows(2)
                    ->maxLength(500)
                    ->helperText('Short summary for listings'),
                RichEditor::make('content')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->directory('blogs')
                    ->disk('images')
                    ->visibility('public'),
                DateTimePicker::make('published_at')
                    ->default(now())
                    ->seconds(false),
                Toggle::make('is_active')->default(true),
            ]);
    }
}
