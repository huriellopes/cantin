<?php

namespace App\Filament\Admin\Resources;

use App\Enum\Role as RoleEnum;
use App\Models\Role;
use App\Models\User;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
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

    protected static ?string $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 1;

    public static function canAccess() : bool
    {
        return auth()->user()->hasRole('super-admin');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Acesso do usuário')->schema([
                    Forms\Components\ToggleButtons::make('role_id')
                        ->label('Perfil de Acesso')
                        ->required()
                        ->markAsRequired(false)
                        ->options(Role::all()->sortBy('name')->pluck('name', 'id'))
                        ->grouped()
                        ->reactive()
                        ->default(3)
                        ->columnSpan(2)
                        ->afterStateUpdated(function ($record, $state, $livewire) {
                            if ($record) {
                                $record->role_id = $state;
                                $livewire->dispatch(['role_id' => $state]);
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
            ->deferLoading()
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
                TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->getColor()),
                TextColumn::make('role_id')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->label('Perfil'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role_id')
                    ->label('Perfil')
                    ->relationship('role', 'name'),
                Tables\Filters\SelectFilter::make('status')
//                    ->options(fn ($state) => $state->label()),
            ])
            ->deferFilters()
            ->filtersFormWidth(MaxWidth::ExtraLarge)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Editar usuário')
                    ->label(''),
                Tables\Actions\Action::make('exclude')
                    ->size(10)
                    ->color(Color::Red)
                    ->tooltip('Excluir usuário')
                    ->requiresConfirmation()
                    ->modalHeading('Excluir Usuário')
                    ->modalDescription(' Vocé tem certeza que deseja excluir o usuário?')
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->action(function (User $record) {
                        $record->delete();

                        Notification::make()
                            ->success()
                            ->title('Usuário excluido com sucesso!')
                            ->send();
                    }),
            ]);
    }

    /**
     * @return Builder
     */
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
