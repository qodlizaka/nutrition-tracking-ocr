<?php

namespace App\Filament\Resources;

use App\Enum\Gender;
use App\Enum\UserRole;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\RelationManagers\UserDetailsRelationManager;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Number;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                DateTimePicker::make('email_verified_at')
                    ->translateLabel(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->translateLabel(),
                Select::make('role')
                    ->required()
                    ->options(UserRole::translatedArray())
                    ->translateLabel(),
                DatePicker::make('date_of_birth')
                    ->translateLabel(),
                Select::make('gender')
                    ->required()
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
                TextColumn::make('email')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->translateLabel(),
                SelectColumn::make('role')
                    ->options(UserRole::translatedArray())
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->translateLabel(),
                SelectColumn::make('gender')
                    ->options(Gender::translatedArray())
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('detail.weight')
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::format($state, 2).' Kg')
                    ->label(__('Weight')),
                TextColumn::make('detail.height')
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => Number::format($state, 2).' cm')
                    ->label(__('Height')),
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
            UserDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
