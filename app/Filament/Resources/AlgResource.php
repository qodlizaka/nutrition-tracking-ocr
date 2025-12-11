<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\AlgResource\Pages\ListAlgs;
use App\Filament\Resources\AlgResource\Pages\CreateAlg;
use App\Filament\Resources\AlgResource\Pages\EditAlg;
use App\Filament\Resources\AlgResource\Pages;
use App\Filament\Resources\AlgResource\RelationManagers;
use App\Filament\Resources\AlgResource\RelationManagers\NutritionsRelationManager;
use App\Models\Alg;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlgResource extends Resource
{
    protected static ?string $model = Alg::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                TextInput::make('energy')
                    ->required()
                    ->numeric()
                    ->maxValue(100_000)
                    ->minValue(0)
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('energy')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn(int $state) => $state . " " . __('kcal'))
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            NutritionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlgs::route('/'),
            'create' => CreateAlg::route('/create'),
            'edit' => EditAlg::route('/{record}/edit'),
        ];
    }
}
