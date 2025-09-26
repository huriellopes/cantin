<?php

namespace App\Filament\Admin\Resources\Terreiros;

use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Exception;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\Terreiros\Pages\ListTerreiros;
use App\Filament\Admin\Resources\Terreiros\Pages\CreateTerreiro;
use App\Filament\Admin\Resources\Terreiros\Pages\EditTerreiro;
use App\Actions\Address\FillAddressAction;
use App\Filament\Admin\Resources\TerreiroResource\Pages;
use App\Filament\Exports\TerreiroExporter;
use App\Models\City;
use App\Models\Terreiro;
use App\Traits\Utils;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use UnitEnum;

class TerreiroResource extends Resource
{
    use Utils;

    protected static ?string $model = Terreiro::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-m-home';

    protected static ?string $navigationLabel = 'Terreiros';

    protected static ?string $pluralLabel = 'Terreiros';

    protected static ?string $slug = 'terreiros';

    protected static ?string $modelLabel = 'Terreiro';

    protected static string | \UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Terreiro')
                        ->schema([
                            Fieldset::make()
                                ->label('Dados do Terreiro')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->columnSpanFull()
                                        ->maxLength(255)
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ])
                                        ->label('Nome'),
                                    Select::make('nation_terreiro_id')
                                        ->relationship('nation', 'name')
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute obrigatório.'
                                        ])
                                        ->label('Nação'),
                                    TextInput::make('phone')
                                        ->mask('(99) 9 9999-9999')
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ])
                                        ->label('Telefone'),
                                    TextInput::make('leadership_orunko')
                                        ->required()
                                        ->maxLength(255)
                                        ->label('Orukó ou nome da liderança')
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ]),
                                    Select::make('color_of_leadership')
                                        ->options(config('terreiro.color_of_leadership'))
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute obrigatório.'
                                        ])
                                        ->label('Cor de pele da liderança'),
                                ]),
                            Fieldset::make()
                                ->label('Endereço do Terreiro')
                                ->schema([
                                    TextInput::make('address.zipcode')
                                        ->required()
                                        ->minLength(8)
                                        ->validationMessages([
                                            'required' => 'O campo :attribute obrigatório.'
                                        ])
                                        ->label('Cep')
                                        ->live(onBlur: true)
                                        ->mask('99999-999')
                                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
//                                            if (strlen($state) < 8) {
//                                                return;
//                                            }

                                            $zipCode = self::clearMask($state);

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
                        ]),
                    Step::make('Perguntas')
                        ->columnSpanFull()
                        ->schema([
                            Fieldset::make()
                                ->schema([
                                    Select::make('question.type_people_id')
                                        ->relationship('question.typePeople', 'name')
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ])
                                        ->label('Qual a identidade de gênero da liderança do terreiro?'),
                                    TextInput::make('question.number_of_children_of_saint')
                                        ->required()
                                        ->numeric()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute obrigatório.',
                                        ])
                                        ->label('Quantos membros ativos o terreiro tem?'),
                                    TextInput::make('question.number_of_children_of_saint_trans')
                                        ->required()
                                        ->numeric()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.',
                                            'numeric' => 'O campo :attribute é numérico.'
                                        ])
                                        ->label('Quantos pessoas trans/travesti são integrantes desse terreiro?'),
                                    Select::make('question.trans_men_and_women')
                                        ->options(config('terreiro.trans_men_and_women'))
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ])
                                        ->label('As pessoas trans do terreiro usam roupas segundo o gênero que se identificam? Ex.: Mulheres trans usam saia? Homens trans usam calça?'),
                                    Select::make('question.name_gender')
                                        ->options(config('terreiro.name_gender'))
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ])
                                        ->label('As pessoas trans do terreiro são chamadas pelo nome e gênero que desejam?'),
                                    Select::make('question.fully_welcomes')
                                        ->options(config('terreiro.fully_welcomes'))
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute obrigatório.'
                                        ])
                                        ->label('A família espiritual acolhe integralmente as pessoas trans do terreiro ou a liderança ainda precisa mediar as relações?'),
                                    Select::make('question.respect_for_trans_people')
                                        ->options(config('terreiro.respect_for_trans_people'))
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ])
                                        ->label('O terreiro fez alguma ação de conscientização da necessidade de acolhimento respeitoso de pessoas trans em suas dependências?'),
                                    Select::make('question.suffered_aggregation')
                                        ->options(config('terreiro.suffered_aggregation'))
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.'
                                        ])
                                        ->label('A liderança e as pessoas trans do terreiro foram hostilizadas quando os demais terreiros souberam que essas pessoas são respeitadas na casa?'),
                                    Select::make('question.inclusion_of_the_name_of_the_land')
                                        ->options(config('terreiro.inclusion_of_the_name_of_the_land'))
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.',
                                        ])
                                        ->label('Podemos incluir o nome e o contato do seu terreiro na lista de indicações de casas trans-inclusivas para Orientar'),
                                    Select::make('question.suggestion_id')
                                        ->options(config('terreiro.suggestion_id'))
                                        ->required()
                                        ->label('Sugestão')
                                        ->live()
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório',
                                        ]),
                                    Textarea::make('question.suggestion_text')
                                        ->visible(fn (Get $get) => $get('suggestion_id') === 1 || $get('suggestion_id') === 2 || $get('sugestion_id') === 3)
                                        ->required()
                                        ->columnSpanFull()
                                        ->maxLength(255)
                                        ->validationMessages([
                                            'required' => 'O campo :attribute é obrigatório.',
                                        ])
                                        ->label('Sugestão'),
                                ]),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('#'),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Telefone'),
                TextColumn::make('leadership_orunko')
                    ->searchable()
                    ->wrap()
                    ->label('Orukó da liderança'),
                TextColumn::make('nation.name')
                    ->label('Nação'),
                TextColumn::make('address.state.name')
                    ->label('Estado'),
                TextColumn::make('address.city.name')
                    ->label('Cidade'),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Exportar')
                    ->color(Color::Blue)
                    ->icon('heroicon-m-arrow-down-tray')
                    ->exporter(TerreiroExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                        ExportFormat::Xlsx
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->color('primary')
                    ->modalWidth('lg')
                    ->modalHeading('Editar Terreiro')
                    ->modalDescription('Edite os dados do terreiro')
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->modalWidth('lg')
                    ->modalHeading('Excluir Terreiro')
                    ->modalDescription('Tem certeza que deseja excluir este terreiro?')
                    ->label(''),
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
            'index' => ListTerreiros::route('/'),
            'create' => CreateTerreiro::route('/create'),
            'edit' => EditTerreiro::route('/{record}/edit'),
        ];
    }
}
