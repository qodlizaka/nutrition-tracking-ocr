<?php

namespace App\Filament\Resources\FoodResource\RelationManagers;

use App\Models\Nutrition;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NutritionsRelationManager extends RelationManager
{
    protected static string $relationship = 'nutritions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->maxLength(255)
                    ->translateLabel(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->translateLabel(),
                TextColumn::make('value')
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->options(Nutrition::pluck('name', 'id')),
                        TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->maxLength(255)
                            ->translateLabel(),
                    ])
                    ->modalWidth(Width::Small),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth(Width::Small),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
