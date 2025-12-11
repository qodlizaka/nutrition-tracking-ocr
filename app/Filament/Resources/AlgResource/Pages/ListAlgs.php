<?php

namespace App\Filament\Resources\AlgResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\AlgResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlgs extends ListRecords
{
    protected static string $resource = AlgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
