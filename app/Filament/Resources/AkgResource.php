<?php

namespace App\Filament\Resources;

use App\Enum\Gender;
use App\Filament\Resources\AkgResource\Pages;
use App\Filament\Resources\AkgResource\RelationManagers;
use App\Filament\Resources\AkgResource\RelationManagers\NutritionsRelationManager;
use App\Models\Akg;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AkgResource extends Resource
{
    protected static ?string $model = Akg::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Forms\Components\TextInput::make('min_age')
                    ->required()
                    ->numeric()
                    ->translateLabel(),
                Forms\Components\TextInput::make('max_age')
                    ->required()
                    ->numeric()
                    ->translateLabel(),
                Forms\Components\Select::make('gender')
                    ->options(Gender::translatedArray())
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('min_age')
                    ->sortable()
                    ->formatStateUsing(fn(float $state) => $state . " " . __('years'))
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('max_age')
                    ->sortable()
                    ->formatStateUsing(fn(float $state) => $state . " " . __('years'))
                    ->translateLabel(),
                Tables\Columns\SelectColumn::make('gender')
                    ->options(Gender::translatedArray())
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAkgs::route('/'),
            'create' => Pages\CreateAkg::route('/create'),
            'edit' => Pages\EditAkg::route('/{record}/edit'),
        ];
    }
}
