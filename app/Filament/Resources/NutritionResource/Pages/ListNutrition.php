<?php

namespace App\Filament\Resources\NutritionResource\Pages;

use App\Filament\Resources\NutritionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNutrition extends ListRecords
{
    protected static string $resource = NutritionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
