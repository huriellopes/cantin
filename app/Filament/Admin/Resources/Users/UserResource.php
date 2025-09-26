<?php

namespace App\Filament\Admin\Resources\Users;

use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Enum\Status as EnumStatus;
use App\Filament\Exports\UserExporter;
use App\Models\Role;
use App\Models\User;
use App\Filament\Admin\Resources\UserResource\Pages;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Usuário';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?string $breadcrumb = 'Usários';

    protected static ?string $navigationLabel = 'Usuários';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 1;

    public static function canAccess() : bool
    {
        return auth()->user()->hasRole('super-admin');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Acesso do usuário')
                    ->columnSpanFull()
                    ->schema([
                        ToggleButtons::make('role_id')
                            ->label('Perfil de Acesso')
                            ->required()
                            ->markAsRequired(false)
                            ->options(Role::all()->sortBy('name')->pluck('name', 'id'))
                            ->grouped()
                            ->reactive()
                            ->default(3)
                            ->columnSpanFull()
                            ->visible(fn () => auth()->user()->hasRole('super-admin'))
                            ->afterStateUpdated(function ($record, $state, $livewire) {
                                if ($record) {
                                    $record->role_id = $state;
                                    $livewire->dispatch(['role_id' => $state]);
                                }
                            }),
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->string(),
                        TextInput::make('email')
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
            ->headerActions([
                ExportAction::make()
                    ->label('Exportar')
                    ->color(Color::Blue)
                    ->icon('heroicon-m-arrow-down-tray')
                    ->exporter(UserExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                        ExportFormat::Xlsx
                    ]),
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->visible(fn () => auth()->user()->hasRole('super-admin'))
                    ->label('Perfil')
                    ->relationship('role', 'name'),
            ])
            ->filtersFormWidth(Width::ExtraLarge)
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record) => auth()->user()->hasRole('super-admin'))
                    ->size(10)
                    ->color(Color::Blue)
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Editar')
                    ->label(''),
                Action::make('exclude')
                    ->size(10)
                    ->color(Color::Red)
                    ->tooltip('Excluir usuário')
                    ->requiresConfirmation()
                    ->modalHeading('Excluir Usuário')
                    ->modalDescription(' Vocé tem certeza que deseja excluir o usuário?')
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->action(function (User $record) {
                        if ($record->id === auth()->user()->id) {
                            Notification::make()
                                ->warning()
                                ->title('Você não pode excluir seu usuário.')
                                ->send();
                            return;
                        }

                        $record->delete();

                        Notification::make()
                            ->success()
                            ->title('Usuário excluido com sucesso!')
                            ->send();
                    }),
                Action::make('enable')
                    ->icon('heroicon-o-check-circle')
                    ->size(10)
                    ->tooltip('Habilitar usuário')
                    ->color(Color::Green)
                    ->label('')
                    ->modalHeading('Ativar')
                    ->modalDescription('Você tem certeza que deseja habilitar o usuário?')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === EnumStatus::INACTIVE)
                    ->action(function (User $record) {
                        if ($record->id === auth()->user()->id) {
                            Notification::make()
                                ->warning()
                                ->title('Você não pode ativar seu usuário.')
                                ->send();
                            return;
                        }

                        if ($record->status === EnumStatus::ACTIVE) {
                            Notification::make()
                                ->warning()
                                ->title('Usuário já está ativo!')
                                ->send();
                            return;
                        }

                        $record->update(['status' => EnumStatus::ACTIVE]);

                        Notification::make()
                            ->success()
                            ->title('Usuário ativado com sucesso!')
                            ->send();
                    }),
                Action::make('disable')
                    ->icon('heroicon-o-x-circle')
                    ->size(10)
                    ->color(Color::Red)
                    ->tooltip('Desabilitar usuário')
                    ->label('')
                    ->modalHeading('Desabilitar')
                    ->modalDescription('Você tem certeza que deseja Desabilitar o usuário?')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === EnumStatus::ACTIVE)
                    ->action(function (User $record) {
                        if ($record->id === auth()->user()->id) {
                            Notification::make()
                                ->warning()
                                ->title('Você não pode inativar seu usuário.')
                                ->send();
                            return;
                        }

                        if ($record->status === EnumStatus::INACTIVE) {
                            Notification::make()
                                ->warning()
                                ->title('O usuário já está desativado!')
                                ->send();

                            return;
                        }

                        $record->update(['status' => EnumStatus::INACTIVE]);

                        Notification::make()
                            ->success()
                            ->title('Usuário desativado com sucesso!')
                            ->send();
                    }),
                Action::make('reset-pass')
                    ->icon('heroicon-o-key')
                    ->tooltip('Resetar senha')
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading('Reset de senha')
                    ->modalDescription('Tem certeza que deseja resetar a senha deste usuário?')
                    ->visible(fn (User $record) => $record->hasRole('user') || $record->hasRole('admin'))
                    ->action(function (User $record) {
                        $record->password = bcrypt('secret1234');
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Sucesso')
                            ->body('Senha resetada com sucesso! Informe ao usuário que a nova senha é "secret123"')
                            ->send();
                    })
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

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
