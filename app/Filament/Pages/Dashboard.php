<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BasePage
{

    public static function getNavigationLabel(): string
    {
        return __('Admin panel');
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-shield-check';
    }

}
