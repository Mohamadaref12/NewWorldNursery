<?php

namespace App\Filament\Resources\InstagramPosts;

use App\Filament\Resources\InstagramPosts\Pages\CreateInstagramPost;
use App\Filament\Resources\InstagramPosts\Pages\EditInstagramPost;
use App\Filament\Resources\InstagramPosts\Pages\ListInstagramPosts;
use App\Filament\Resources\InstagramPosts\Pages\ViewInstagramPost;
use App\Filament\Resources\InstagramPosts\Schemas\InstagramPostForm;
use App\Filament\Resources\InstagramPosts\Schemas\InstagramPostInfolist;
use App\Filament\Resources\InstagramPosts\Tables\InstagramPostsTable;
use App\Models\InstagramPost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class InstagramPostResource extends Resource
{
    protected static ?string $model = InstagramPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCamera;

    protected static ?string $navigationLabel = 'Stored Posts';

    protected static ?string $modelLabel = 'Instagram post';

    protected static ?string $pluralModelLabel = 'Instagram posts';

    protected static string|UnitEnum|null $navigationGroup = 'Instagram';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return InstagramPostForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InstagramPostInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstagramPostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('translations');
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        return $record?->display_name;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstagramPosts::route('/'),
            'create' => CreateInstagramPost::route('/create'),
            'view' => ViewInstagramPost::route('/{record}'),
            'edit' => EditInstagramPost::route('/{record}/edit'),
        ];
    }
}
