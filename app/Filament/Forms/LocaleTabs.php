<?php

namespace App\Filament\Forms;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Contracts\Support\Htmlable;

class LocaleTabs
{
    /**
     * @param  array<int, mixed>  $enComponents
     * @param  array<int, mixed>  $arComponents
     */
    public static function make(array $enComponents, array $arComponents, string | Htmlable | null $label = 'Translations'): Tabs
    {
        return Tabs::make($label)
            ->persistTabInQueryString('locale')
            ->columnSpanFull()
            ->tabs([
                Tab::make('English')
                    ->icon('heroicon-o-language')
                    ->schema($enComponents),
                Tab::make('العربية')
                    ->icon('heroicon-o-language')
                    ->schema($arComponents),
            ]);
    }
}
