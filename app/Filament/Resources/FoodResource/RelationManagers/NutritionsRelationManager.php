<?php

namespace App\Filament\Resources\FoodResource\RelationManagers;

use App\Models\Nutrition;
use Filament\Actions\AttachAction;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
