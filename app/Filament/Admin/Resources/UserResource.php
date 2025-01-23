<?php

namespace App\Filament\Admin\Resources;

use App\Enum\LevelEnum;
use App\Models\Level;
use App\Models\User;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $breadcrumb = 'Usários';

    protected static ?string $navigationLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(200)
                    ->string()
                    ->label('Nome'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(200)
                    ->email()
                    ->label('Email'),
                Forms\Components\Select::make('level_id')
                    ->required()
                    ->label('Perfil')
                    ->options(Level::all()->pluck('level', 'id'))
                    ->native('Selecione...'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->label('#'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('email')
                    ->label('Email'),
                IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn ($state) => match ($state->value) {
                        1 => 'heroicon-s-check-circle',
                        0 => 'heroicon-s-x-circle',
                    })
                    ->color(fn ($state): string => match ($state->value) {
                        1 => 'success',
                        0 => 'danger',
                    })->boolean(),
                TextColumn::make('level_id')
                    ->formatStateUsing(fn ($state) => $state === LevelEnum::SUPER ? 'Super Usuário' : 'Administrador')
                    ->label('Perfil'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('id', '<>', auth()->user()->id);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
