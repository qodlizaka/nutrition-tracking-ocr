<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enum\PhysicalActivityLevel;
use App\Models\UserDetail;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'userDetails';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('weight')
                    ->numeric()
                    ->maxValue(1000)
                    ->minValue(0)
                    ->required()
                    ->translateLabel(),
                TextInput::make('height')
                    ->required()
                    ->maxValue(1000)
                    ->minValue(0)
                    ->numeric()
                    ->translateLabel(),
                Select::make('activity_level')
                    ->required()
                    ->options(PhysicalActivityLevel::translatedArray())
                    ->translateLabel(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(fn (UserDetail $record) => $record->created_at->diffForHumans())
            ->columns([
                TextColumn::make('weight')
                    ->translateLabel(),
                TextColumn::make('height')
                    ->translateLabel(),
                TextColumn::make('activity_level')
                    ->formatStateUsing(fn (PhysicalActivityLevel $state) => __(Str::headline($state->name)))
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->since()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalWidth(Width::Small),
            ])
            ->recordActions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->isUser();
    }
}
