<?php

namespace App\Filament\Resources\InstagramPosts\Schemas;

use App\Models\InstagramPost;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

class InstagramPostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Flex::make([
                    Section::make()
                        ->grow()
                        ->schema([
                            ImageEntry::make('image')
                                ->hiddenLabel()
                                ->disk('images')
                                ->imageWidth('100%')
                                ->imageHeight('auto')
                                ->extraImgAttributes([
                                    'class' => 'w-full rounded-xl object-cover aspect-square',
                                ])
                                ->url(fn (InstagramPost $record): ?string => $record->permalink)
                                ->openUrlInNewTab(),
                        ]),
                    Grid::make(1)
                        ->grow()
                        ->schema([
                            Section::make('Details')
                                ->icon(Heroicon::OutlinedInformationCircle)
                                ->compact()
                                ->schema([
                                    TextEntry::make('is_active')
                                        ->label('Status')
                                        ->badge()
                                        ->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Hidden')
                                        ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                                    TextEntry::make('sort_order')
                                        ->label('Display order'),
                                    TextEntry::make('permalink')
                                        ->label('Instagram URL')
                                        ->placeholder('No link')
                                        ->icon(fn (?string $state): mixed => filled($state) ? Heroicon::OutlinedArrowTopRightOnSquare : false)
                                        ->url(fn (InstagramPost $record): ?string => $record->permalink)
                                        ->openUrlInNewTab()
                                        ->color('primary')
                                        ->columnSpanFull(),
                                    TextEntry::make('created_at')
                                        ->label('Created')
                                        ->dateTime('M j, Y g:i A'),
                                    TextEntry::make('updated_at')
                                        ->label('Updated')
                                        ->since(),
                                ])
                                ->columns(2),
                            Section::make('Caption')
                                ->icon(Heroicon::OutlinedLanguage)
                                ->compact()
                                ->schema([
                                    TextEntry::make('caption_en')
                                        ->label('English')
                                        ->placeholder('No caption')
                                        ->weight(FontWeight::Medium)
                                        ->getStateUsing(fn (InstagramPost $record): ?string => $record->translationValue('alt', 'en')),
                                    TextEntry::make('caption_ar')
                                        ->label('العربية')
                                        ->placeholder('لا يوجد تعليق')
                                        ->weight(FontWeight::Medium)
                                        ->extraAttributes(['dir' => 'rtl'])
                                        ->getStateUsing(fn (InstagramPost $record): ?string => $record->translationValue('alt', 'ar')),
                                ])
                                ->columns(['default' => 1, 'md' => 2]),
                        ]),
                ])
                    ->from('lg')
                    ->verticallyAlignStart(),
            ]);
    }
}
