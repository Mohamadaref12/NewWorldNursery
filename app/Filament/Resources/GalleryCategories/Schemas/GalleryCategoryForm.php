<?php

namespace App\Filament\Resources\GalleryCategories\Schemas;

use App\Filament\Forms\LocaleTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class GalleryCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                LocaleTabs::make(
                    [
                        TextInput::make('en.name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state, string $operation): void {
                                if ($operation === 'create') {
                                    $set('en.slug', Str::slug((string) $state));
                                }
                            }),
                        TextInput::make('en.slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: 'gallery_category_translations',
                                column: 'slug',
                                ignoreRecord: false,
                                modifyRuleUsing: fn (Unique $rule, $get, $record) => self::uniqueSlugRule($rule, $record, 'en'),
                            ),
                    ],
                    [
                        TextInput::make('ar.name')
                            ->label('الاسم')
                            ->maxLength(255)
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state, string $operation): void {
                                if ($operation === 'create' && filled($state)) {
                                    $set('ar.slug', Str::slug((string) $state));
                                }
                            }),
                        TextInput::make('ar.slug')
                            ->label('الرابط')
                            ->maxLength(255)
                            ->extraInputAttributes(['dir' => 'rtl'])
                            ->unique(
                                table: 'gallery_category_translations',
                                column: 'slug',
                                ignoreRecord: false,
                                modifyRuleUsing: fn (Unique $rule, $get, $record) => self::uniqueSlugRule($rule, $record, 'ar'),
                            ),
                    ],
                ),
                Section::make('Details')->schema([
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_active')->default(true),
                ])->columns(2)->columnSpanFull(),
            ]);
    }

    protected static function uniqueSlugRule(Unique $rule, mixed $record, string $locale): Unique
    {
        $rule->where('locale', $locale);

        if ($record) {
            $rule->ignore($record->getKey(), 'gallery_category_id');
        }

        return $rule;
    }
}
