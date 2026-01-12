<?php

namespace App\Filament\Resources;

use App\Enum\Gender;
use App\Filament\Resources\AkgResource\Pages\CreateAkg;
use App\Filament\Resources\AkgResource\Pages\EditAkg;
use App\Filament\Resources\AkgResource\Pages\ListAkgs;
use App\Filament\Resources\AkgResource\RelationManagers\NutritionsRelationManager;
use App\Models\Akg;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AkgResource extends Resource
{
    protected static ?string $model = Akg::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                TextInput::make('min_age')
                    ->required()
                    ->numeric()
                    ->translateLabel(),
                TextInput::make('max_age')
                    ->required()
                    ->numeric()
                    ->translateLabel(),
                Select::make('gender')
                    ->options(Gender::translatedArray())
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
                TextColumn::make('min_age')
                    ->sortable()
                    ->formatStateUsing(fn (float $state) => $state.' '.__('years'))
                    ->translateLabel(),
                TextColumn::make('max_age')
                    ->sortable()
                    ->formatStateUsing(fn (float $state) => $state.' '.__('years'))
                    ->translateLabel(),
                SelectColumn::make('gender')
                    ->options(Gender::translatedArray())
                    ->sortable()
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
            'index' => ListAkgs::route('/'),
            'create' => CreateAkg::route('/create'),
            'edit' => EditAkg::route('/{record}/edit'),
        ];
    }
}
