<?php

namespace App\Filament\Resources\InstagramPosts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class InstagramPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Media')
                    ->description('Square images look best in the Instagram feed.')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Post image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1', '4:5', '16:9'])
                            ->imageCropAspectRatio('1:1')
                            ->panelLayout('integrated')
                            ->imagePreviewHeight('320')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->directory('instagram')
                            ->disk('images')
                            ->visibility('public')
                            ->required()
                            ->helperText('JPG, PNG or WebP — max 5 MB.'),
                    ])
                    ->columnSpan(1),
                Grid::make(1)
                    ->schema([
                        Section::make('Post details')
                            ->description('Link, order, and visibility on the website.')
                            ->icon(Heroicon::OutlinedLink)
                            ->schema([
                                TextInput::make('permalink')
                                    ->label('Instagram URL')
                                    ->placeholder('https://www.instagram.com/p/...')
                                    ->url()
                                    ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                                    ->maxLength(255)
                                    ->helperText('Optional. Opens this post on Instagram.'),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('sort_order')
                                            ->label('Display order')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText('Lower numbers appear first.'),
                                        Toggle::make('is_active')
                                            ->label('Published')
                                            ->helperText('Show this post on the site.')
                                            ->onColor('success')
                                            ->offColor('gray')
                                            ->inline(false)
                                            ->default(true),
                                    ]),
                            ]),
                        Section::make('Caption')
                            ->description('Accessibility text shown as alt / caption.')
                            ->icon(Heroicon::OutlinedLanguage)
                            ->schema([
                                Tabs::make('Caption languages')
                                    ->contained(false)
                                    ->tabs([
                                        Tab::make('English')
                                            ->icon(Heroicon::OutlinedLanguage)
                                            ->schema([
                                                Textarea::make('en.alt')
                                                    ->label('Caption')
                                                    ->placeholder('Describe the photo…')
                                                    ->rows(4)
                                                    ->maxLength(500)
                                                    ->helperText('Used for alt text and hover captions.'),
                                            ]),
                                        Tab::make('العربية')
                                            ->icon(Heroicon::OutlinedLanguage)
                                            ->schema([
                                                Textarea::make('ar.alt')
                                                    ->label('التعليق')
                                                    ->placeholder('صف الصورة…')
                                                    ->rows(4)
                                                    ->maxLength(500)
                                                    ->extraInputAttributes(['dir' => 'rtl']),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(2),
            ]);
    }
}
