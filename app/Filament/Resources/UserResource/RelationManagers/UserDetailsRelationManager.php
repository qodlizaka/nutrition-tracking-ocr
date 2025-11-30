<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enum\PhysicalActivityLevel;
use App\Models\User;
use App\Models\UserDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class UserDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'userDetails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('weight')
                    ->numeric()
                    ->maxValue(1000)
                    ->minValue(0)
                    ->required()
                    ->translateLabel(),
                Forms\Components\TextInput::make('height')
                    ->required()
                    ->maxValue(1000)
                    ->minValue(0)
                    ->numeric()
                    ->translateLabel(),
                Forms\Components\Select::make('activity_level')
                    ->required()
                    ->options(PhysicalActivityLevel::translatedArray())
                    ->translateLabel(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(fn(UserDetail $record) => $record->created_at->diffForHumans())
            ->columns([
                Tables\Columns\TextColumn::make('weight')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('height')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('activity_level')
                    ->formatStateUsing(fn(PhysicalActivityLevel $state) => __(Str::headline($state->name)))
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth(MaxWidth::Small),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
