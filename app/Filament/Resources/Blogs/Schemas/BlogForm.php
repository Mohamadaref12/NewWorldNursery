<?php

namespace App\Filament\Resources\Blogs\Schemas;

use App\Filament\Forms\LocaleTabs;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                LocaleTabs::make(
                    [
                        TextInput::make('en.title')
                            ->label('Title')
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
                                table: 'blog_translations',
                                column: 'slug',
                                ignoreRecord: false,
                                modifyRuleUsing: fn (Unique $rule, $get, $record) => self::uniqueSlugRule($rule, $record, 'en'),
                            )
                            ->helperText('Used in the API URL'),
                        Textarea::make('en.excerpt')
                            ->label('Excerpt')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Short summary for listings'),
                        RichEditor::make('en.content')
                            ->label('Content')
                            ->columnSpanFull(),
                    ],
                    [
                        TextInput::make('ar.title')
                            ->label('العنوان')
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
                                table: 'blog_translations',
                                column: 'slug',
                                ignoreRecord: false,
                                modifyRuleUsing: fn (Unique $rule, $get, $record) => self::uniqueSlugRule($rule, $record, 'ar'),
                            ),
                        Textarea::make('ar.excerpt')
                            ->label('المختصر')
                            ->rows(2)
                            ->maxLength(500)
                            ->extraInputAttributes(['dir' => 'rtl']),
                        RichEditor::make('ar.content')
                            ->label('المحتوى')
                            ->columnSpanFull(),
                    ],
                ),
                Section::make('Details')->schema([
                    FileUpload::make('image')
                        ->image()
                        ->directory('blogs')
                        ->disk('images')
                        ->visibility('public'),
                    DateTimePicker::make('published_at')
                        ->default(now())
                        ->seconds(false),
                    Toggle::make('is_active')->default(true),
                ])->columns(2)->columnSpanFull(),
            ]);
    }

    protected static function uniqueSlugRule(Unique $rule, mixed $record, string $locale): Unique
    {
        $rule->where('locale', $locale);

        if ($record) {
            $rule->ignore($record->getKey(), 'blog_id');
        }

        return $rule;
    }
}
