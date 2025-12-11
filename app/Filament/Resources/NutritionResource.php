<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\NutritionResource\Pages\ListNutrition;
use App\Filament\Resources\NutritionResource\Pages\CreateNutrition;
use App\Filament\Resources\NutritionResource\Pages\EditNutrition;
use App\Enum\NutritionCategory;
use App\Enum\NutritionGroup;
use App\Filament\Resources\NutritionResource\Pages;
use App\Filament\Resources\NutritionResource\RelationManagers;
use App\Models\Nutrition;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NutritionResource extends Resource
{
    protected static ?string $model = Nutrition::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                TextInput::make('unit')
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
                Select::make('category')
                    ->required()
                    ->options(NutritionCategory::translatedArray())
                    ->translateLabel(),
                Select::make('group')
                    ->required()
                    ->options(NutritionGroup::translatedArray())
                    ->translateLabel(),
                Textarea::make('description')
                    ->required()
                    ->maxLength(1024)
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
                TextColumn::make('unit')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('description')
                    ->searchable()
                    ->translateLabel(),
                SelectColumn::make('category')
                    ->options(NutritionCategory::translatedArray())
                    ->sortable()
                    ->translateLabel(),
                SelectColumn::make('group')
                    ->options(NutritionGroup::translatedArray())
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
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ], RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListNutrition::route('/'),
            'create' => CreateNutrition::route('/create'),
            'edit' => EditNutrition::route('/{record}/edit'),
        ];
    }
}
