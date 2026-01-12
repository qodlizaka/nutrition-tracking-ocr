<?php

namespace App\Filament\Resources\NutritionResource\Pages;

use App\Filament\Resources\NutritionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNutrition extends EditRecord
{
    protected static string $resource = NutritionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
