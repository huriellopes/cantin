<?php

namespace App\Filament\Admin\Resources;

use App\Enum\Role as RoleEnum;
use App\Models\Role;
use App\Models\User;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
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

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $breadcrumb = 'Usários';

    protected static ?string $navigationLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Acesso do usuário')->schema([
                    Forms\Components\ToggleButtons::make('level_id')
                        ->label('Perfil de Acesso')
                        ->required()
                        ->markAsRequired(false)
                        ->options(Role::all()->sortBy('level')->pluck('level', 'id'))
                        ->grouped()
                        ->reactive()
                        ->default(3)
                        ->columnSpan(2)
                        ->afterStateUpdated(function ($record, $state, $livewire) {
                            if ($record) {
                                $record->level_id = $state;
                                $livewire->dispatch(['level_id' => $state]);
                            }
                        }),
                    Forms\Components\TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->string(),
                    Forms\Components\TextInput::make('email')
                        ->label('E-mail')
                        ->required()
                        ->email(),
                ]),
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
                    ->formatStateUsing(fn ($state) => $state === Role::SUPER ? 'Super Usuário' : 'Administrador')
                    ->label('Perfil'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level_id')
                    ->label('Perfil')
                    ->relationship('level', 'level'),
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        1 => 'Ativo',
                        0 => 'Inativo',
                    ]),
            ])
            ->filtersFormWidth(MaxWidth::ExtraLarge)
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    /**
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
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
