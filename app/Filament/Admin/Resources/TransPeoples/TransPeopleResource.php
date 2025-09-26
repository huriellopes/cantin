<?php

namespace App\Filament\Admin\Resources\TransPeoples;

use Filament\Forms\Components\TextInput;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\TransPeoples\Pages\ListTransPeople;
use App\Filament\Admin\Resources\TransPeoples\Pages\CreateTransPeople;
use App\Filament\Admin\Resources\TransPeoples\Pages\EditTransPeople;
use App\Actions\Address\FillAddressAction;
use App\Enum\Status;
use App\Filament\Admin\Resources\TransPeopleResource\Pages;
use App\Models\City;
use App\Models\PartnerEntity;
use App\Models\TransPeople;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use UnitEnum;

class TransPeopleResource extends Resource
{
    protected static ?string $model = TransPeople::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Pessoas Trans';

    protected static ?string $pluralLabel = 'Pessoas Trans';

    protected static ?string $modelLabel = 'Pessoa Trans';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->label('Dados Pessoais')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->required()
                            ->string()
                            ->label(__('Name')),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->label('E-mail'),
                        TextInput::make('phone')
                            ->required()
                            ->mask('(99) 9 9999-9999')
                            ->string()
                            ->label(__('Phone')),
                    ]),
                Fieldset::make()
                    ->label('Endereço')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('address.zipcode')
                            ->required()
                            ->mask('99999-999')
                            ->maxLength(9)
                            ->label('Cep')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                $zipcode = Str::replace('-', '', $get('address.zipcode'));

                                if (!$zipcode) {
                                    $set('address.address', '');
                                    $set('address.neighborhood', '');
                                    $set('address.state_id', '');
                                    $set('address.city_id', '');
                                    $set('address.latitude', '');
                                    $set('address.longitude', '');

                                    Notification::make()
                                        ->warning()
                                        ->title('Cep inválido!')
                                        ->send();
                                }

                                try {
                                    $address = FillAddressAction::exec($zipcode);

                                    if (!empty($address)) {
                                        $set('address.address', $address->address);
                                        $set('address.neighborhood', $address->neighborhood);
                                        $set('address.state_id', $address->state);
                                        $set('address.city_id', $address->city);
                                        $set('address.latitude', $address->latitude);
                                        $set('address.longitude', $address->longitude);
                                    }
                                } catch (Exception $e) {
                                    Log::error($e->getMessage(), [
                                        'line' => $e->getLine(),
                                        'file' => $e->getFile()
                                    ]);
                                    Notification::make()
                                        ->danger()
                                        ->title('Erro ao buscar CEP')
                                        ->send();
                                }
                            }),
                        TextInput::make('address.address')
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute obrigatório.'
                            ])
                            ->label('Endereço'),
                        TextInput::make('address.complement')
                            ->label('Complemento'),
                        TextInput::make('address.neighborhood')
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute obrigatório.'
                            ])
                            ->label('Bairro'),
                        Select::make('address.state_id')
                            ->relationship('address.state', 'name')
                            ->required()
                            ->live()
                            ->validationMessages([
                                'required' => 'O campo :attribute obrigatório.'
                            ])
                            ->label('Estado'),
                        Select::make('address.city_id')
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute obrigatório.'
                            ])
                            ->label('Cidade')
                            ->options(function (Get $get) {
                                $state = $get('address.state_id');

                                if (!$state) {
                                    return [];
                                }

                                return City::query()
                                    ->where('state_id', '=', $state)
                                    ->get()
                                    ->pluck('name', 'id');
                            }),
                        Hidden::make('address.latitude')
                            ->label('Latitude'),
                        Hidden::make('address.longitude')
                            ->label('Longitude'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('ID'),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('email')
                    ->label('E-mail'),
                TextColumn::make('address.city.name')
                    ->searchable()
                    ->label('Cidade'),
                TextColumn::make('address.state.name')
                    ->searchable()
                    ->label('Estado'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (Status $state) => $state->getColor())
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->searchable()
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Editar')
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->tooltip('Excluir')
                    ->color('danger')
                    ->modalHeading('Excluir')
                    ->modalDescription('Tem certeza que deseja excluir?')
                    ->requiresConfirmation()
                    ->label(''),
                Action::make('enable')
                    ->icon('heroicon-o-check-circle')
                    ->tooltip('Ativar')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Ativar')
                    ->modalDescription('Tem certeza que deseja ativar a entidade parceira?')
                    ->visible(fn ($record) => $record->status === Status::INACTIVE)
                    ->label('')
                    ->action(function (PartnerEntity $record) {
                        if ($record->status === Status::ACTIVE) {
                            Notification::make()
                                ->warning()
                                ->title('A entidade parceira já está ativa')
                                ->send();
                            return;
                        }

                        $record->status = Status::ACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Entidade parceira ativada com sucesso')
                            ->send();
                    }),
                Action::make('disable')
                    ->icon('heroicon-o-x-circle')
                    ->tooltip('Desativar')
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading('Desativar')
                    ->modalDescription('Tem certeza que deseja desativar a entidade parceira?')
                    ->visible(fn ($record) => $record->status === Status::ACTIVE)
                    ->action(function (PartnerEntity $record) {
                        if ($record->status === Status::INACTIVE) {
                            Notification::make()
                                ->warning()
                                ->title('A entidade parceira já se encontra desativada.')
                                ->send();
                            return;
                        }

                        $record->status = Status::INACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Entidade parceira desativada com sucesso')
                            ->send();
                    })
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
            'index' => ListTransPeople::route('/'),
            'create' => CreateTransPeople::route('/create'),
            'edit' => EditTransPeople::route('/{record}/edit'),
        ];
    }
}
