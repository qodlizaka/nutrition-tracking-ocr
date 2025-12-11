<?php

namespace App\Filament\Resources\AkgResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AkgResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAkg extends EditRecord
{
    protected static string $resource = AkgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
