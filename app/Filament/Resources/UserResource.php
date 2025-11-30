<?php

namespace App\Filament\Resources;

use App\Enum\Gender;
use App\Enum\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\UserDetailsRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Forms\Components\DateTimePicker::make('email_verified_at')
                ->translateLabel(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Forms\Components\Select::make('role')
                    ->required()
                    ->options(UserRole::translatedArray())
                    ->translateLabel(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->translateLabel(),
                Forms\Components\Select::make('gender')
                    ->required()
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
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\SelectColumn::make('role')
                    ->options(UserRole::translatedArray())
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\SelectColumn::make('gender')
                    ->options(Gender::translatedArray())
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('detail.weight')
                    ->sortable()
                    ->formatStateUsing(fn(float $state): string => Number::format($state, 2) . " Kg")
                    ->label(__('Weight')),
                Tables\Columns\TextColumn::make('detail.height')
                    ->sortable()
                    ->formatStateUsing(fn(float $state): string => Number::format($state, 2) . " cm")
                    ->label(__('Height')),
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
                ])
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
            UserDetailsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
