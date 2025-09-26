<?php

namespace App\Filament\Admin\Resources\NationTerreiros;

use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\NationTerreiros\Pages\ListNationTerreiros;
use App\Filament\Admin\Resources\NationTerreiroResource\Pages;
use App\Models\NationsTerreiro;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class NationTerreiroResource extends Resource
{
    protected static ?string $model = NationsTerreiro::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-s-globe-americas';

    protected static ?string $navigationLabel = 'Nações';

    protected static ?string $pluralLabel = 'Nações';

    protected static ?string $modelLabel = 'Nação';

    protected static string | \UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                            ])
                            ->label('Nome'),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                                'unique' => 'O slug já existe.'
                            ]),
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
                    ->searchable()
                    ->label('#'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(__('Name')),
                TextColumn::make('slug')
                    ->label(__('Slug')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Criar Nação')
                    ->icon('heroicon-m-plus-circle')
                    ->extraModalFooterActions([
                        Action::make('cancel')
                            ->label('Cancelar'),
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading('Editar')
                    ->modalDescription('Tem certeza que deseja editar?')
                    ->tooltip('Editar')
                    ->color('primary')
                    ->icon('heroicon-m-pencil-square'),
                DeleteAction::make()
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading('Excluir')
                    ->modalDescription('Tem certeza que deseja excluir?')
                    ->tooltip('Excluir')
                    ->color('danger')
                    ->icon('heroicon-m-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNationTerreiros::route('/'),
        ];
    }
}
