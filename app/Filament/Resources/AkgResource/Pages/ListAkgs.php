<?php

namespace App\Filament\Resources\AkgResource\Pages;

use App\Filament\Resources\AkgResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAkgs extends ListRecords
{
    protected static string $resource = AkgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
