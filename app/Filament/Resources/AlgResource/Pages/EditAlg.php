<?php

namespace App\Filament\Resources\AlgResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AlgResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlg extends EditRecord
{
    protected static string $resource = AlgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
