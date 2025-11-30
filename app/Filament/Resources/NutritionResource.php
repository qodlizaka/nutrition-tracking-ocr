<?php

namespace App\Filament\Resources;

use App\Enum\NutritionCategory;
use App\Enum\NutritionGroup;
use App\Filament\Resources\NutritionResource\Pages;
use App\Filament\Resources\NutritionResource\RelationManagers;
use App\Models\Nutrition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NutritionResource extends Resource
{
    protected static ?string $model = Nutrition::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Forms\Components\TextInput::make('unit')
                    ->required()
                    ->datalist([
                        'mg',
                        'g',
                        'kg',
                        'kcal',
                        '%',
                        'ml',
                        'l'
                    ])
                    ->maxLength(50)
                    ->translateLabel(),
                Forms\Components\Select::make('category')
                    ->required()
                    ->options(NutritionCategory::translatedArray())
                    ->translateLabel(),
                Forms\Components\Select::make('group')
                    ->required()
                    ->options(NutritionGroup::translatedArray())
                    ->translateLabel(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(1024)
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
                Tables\Columns\TextColumn::make('unit')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\SelectColumn::make('category')
                    ->options(NutritionCategory::translatedArray())
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\SelectColumn::make('group')
                    ->options(NutritionGroup::translatedArray())
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNutrition::route('/'),
            'create' => Pages\CreateNutrition::route('/create'),
            'edit' => Pages\EditNutrition::route('/{record}/edit'),
        ];
    }
}
