<?php

namespace App\Filament\Resources\InstagramPosts;

use App\Filament\Resources\InstagramPosts\Pages\CreateInstagramPost;
use App\Filament\Resources\InstagramPosts\Pages\EditInstagramPost;
use App\Filament\Resources\InstagramPosts\Pages\ListInstagramPosts;
use App\Filament\Resources\InstagramPosts\Schemas\InstagramPostForm;
use App\Filament\Resources\InstagramPosts\Tables\InstagramPostsTable;
use App\Models\InstagramPost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InstagramPostResource extends Resource
{
    protected static ?string $model = InstagramPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Stored Posts';

    protected static ?string $modelLabel = 'Instagram Post';

    protected static ?string $pluralModelLabel = 'Instagram Posts';

    protected static string|UnitEnum|null $navigationGroup = 'Instagram';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return InstagramPostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstagramPostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstagramPosts::route('/'),
            'create' => CreateInstagramPost::route('/create'),
            'edit' => EditInstagramPost::route('/{record}/edit'),
        ];
    }
}
