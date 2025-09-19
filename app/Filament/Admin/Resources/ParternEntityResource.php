<?php

namespace App\Filament\Admin\Resources;

use App\Actions\Address\FillAddressAction;
use App\Enum\Status;
use App\Filament\Admin\Resources\ParternEntityResource\Pages;
use App\Models\City;
use App\Models\PartnerEntity;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use UnitEnum;

class ParternEntityResource extends Resource
{
    protected static ?string $model = PartnerEntity::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-s-users';

    protected static ?string $navigationLabel = 'Entidades Parceiras';

    protected static ?string $pluralLabel = 'Entidades Parceiras';

    protected static ?string $modelLabel = 'Entidade Parceira';

    protected static string | UnitEnum | null $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->label('Dados')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.'
                            ])
                            ->label('Nome'),
                        TextInput::make('email')
                            ->unique()
                            ->email()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                                'unique' => 'Há um parceiro já existente com este e-mail.',
                                'email' => 'O e-mail é inválido.'
                            ])
                            ->label('Email'),
                        TextInput::make('phone')
                            ->mask("(99) 9 9999-9999")
                            ->required()
                            ->label('Telefone'),
                    ]),
                Fieldset::make()
                    ->columnSpanFull()
                    ->label('Endereço')
                    ->schema([
                        TextInput::make('address.zipcode')
                            ->required()
                            ->mask('99999-999')
                            ->label('CEP')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('address.address', null);
                                    $set('address.neighborhood', null);
                                    $set('address.city', null);
                                    $set('address.state', null);
                                }

                                $zipCode = Str::replace('-', '', $state);

                                if (!$zipCode) {
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
                                    $address = FillAddressAction::exec($zipCode);

                                    if (!empty($address)) {
                                        $set('address.address', $address->address);
                                        $set('address.neighborhood', $address->neighborhood);
                                        $set('address.state_id', $address->state);
                                        $set('address.city_id', $address->city);
                                        $set('address.latitude', $address->latitude);
                                        $set('address.longitude', $address->longitude);
                                    }
                                } catch (\Exception $e) {
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
                Fieldset::make()
                    ->columnSpanFull()
                    ->label('Informações')
                    ->schema([
                        Textarea::make('activity_carried_out')
                            ->required()
                            ->columnSpanFull()
                            ->label('Atividades Realizadas'),
                        FileUpload::make('path_image')
                            ->image()
                            ->columnSpanFull()
                            ->directory('partners')
                            ->disk('public')
                            ->required()
                            ->label('Imagem'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable()
                    ->searchable()
                    ->label('Telefone'),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?? 'Cadastrado no site')
                    ->label('Usuário'),
                Tables\Columns\TextColumn::make('address.city.name')
                    ->searchable()
                    ->label('Cidade'),
                Tables\Columns\TextColumn::make('address.state.name')
                    ->searchable()
                    ->label('Estado'),
                Tables\Columns\TextColumn::make('status')
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
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Editar')
                    ->modalDescription('Tem certeza que deseja editar a entidade parceira?')
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->tooltip('Excluir')
                    ->color('danger')
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading('Excluir')
                    ->modalDescription('Tem certeza que deseja excluir a entidade parceira?'),
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
            'index' => Pages\ListParternEntities::route('/'),
            'create' => Pages\CreateParternEntity::route('/create'),
            'edit' => Pages\EditParternEntity::route('/{record}/edit'),
        ];
    }
}
