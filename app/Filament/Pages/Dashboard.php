<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function getHeading(): string
    {
        return 'New World Nursery';
    }

    public function getSubheading(): ?string
    {
        return 'Overview of enquiries, content, and Instagram.';
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int | array
    {
        return 2;
    }
}
