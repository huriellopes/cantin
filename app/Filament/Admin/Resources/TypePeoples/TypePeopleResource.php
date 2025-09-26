<?php

namespace App\Filament\Admin\Resources\TypePeoples;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\TypePeoples\Pages\ListTypePeople;
use App\Filament\Admin\Resources\TypePeopleResource\Pages;
use App\Models\TypePeople;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TypePeopleResource extends Resource
{
    protected static ?string $model = TypePeople::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Genêros';

    protected static ?string $pluralLabel = 'Genêros';

    protected static ?string $modelLabel = "Genêro";

    protected static string | \UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 5;

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
                                'required' => 'O campo :attribute é obrigatório.'
                            ])
                            ->label('Nome'),
                        TextInput::make('description')
                            ->unique()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                                'unique' => 'O slug do genêro já existe. Tente novamente!'
                            ])
                            ->label('Slug'),
                    ])
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
                    ->label('#'),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('slug')
                    ->searchable()
                    ->label('Slug'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-m-plus')
                    ->label('Criar Genêro')
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
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Editar')
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->tooltip('Excluir')
                    ->modalHeading('Excluir')
                    ->modalDescription('Tem certeza que deseja excluir este registro?')
                    ->requiresConfirmation()
                    ->label(''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTypePeople::route('/'),
        ];
    }
}
