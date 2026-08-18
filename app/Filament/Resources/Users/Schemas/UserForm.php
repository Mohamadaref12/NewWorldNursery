<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Account details')
                    ->description('This user can sign in to the New World Nursery admin panel.')
                    ->icon(Heroicon::OutlinedUser)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full name')
                            ->placeholder('e.g. Sara Ahmad')
                            ->prefixIcon(Heroicon::OutlinedUser)
                            ->required()
                            ->maxLength(255)
                            ->autocomplete('name')
                            ->autofocus(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->placeholder('name@example.com')
                            ->prefixIcon(Heroicon::OutlinedEnvelope)
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->autocomplete('email'),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->prefixIcon(Heroicon::OutlinedLockClosed)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->confirmed()
                            ->minLength(8)
                            ->autocomplete('new-password')
                            ->helperText(fn (string $operation): string => $operation === 'edit'
                                ? 'Leave blank to keep the current password.'
                                : 'Use at least 8 characters.'),
                        TextInput::make('password_confirmation')
                            ->label('Confirm password')
                            ->password()
                            ->revealable()
                            ->prefixIcon(Heroicon::OutlinedLockClosed)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(false)
                            ->autocomplete('new-password'),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }
}
