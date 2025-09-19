<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TypeTerreiroResource\Pages;
use App\Models\TypeTerreiro;
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

class TypeTerreiroResource extends Resource
{
    protected static ?string $model = TypeTerreiro::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-c-home';

    protected static string | UnitEnum | null $navigationGroup = 'Cadastros';

    protected static ?string $navigationLabel = 'Tipos de Terreiros';

    protected static ?string $pluralLabel = 'Tipos de Terreiros';

    protected static ?string $label = 'Tipo de Terreiro';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.'
                            ])
                            ->label('Nome'),
                        Forms\Components\TextInput::make('slug')
                            ->unique()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                                'unique' => 'O slug do tipo de terreiro já existe. Tente novamente!',
                            ])
                            ->label('Slug'),
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
                    ->searchable()
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->label('Slug'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Criar Tipo de Terreiro')
                    ->extraModalFooterActions([
                        Action::make('cancel')
                            ->label('Cancelar')
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->tooltip('Editar')
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->tooltip('Excluir')
                    ->requiresConfirmation()
                    ->modalHeading('Excluir Tipo de Terreiro')
                    ->modalDescription('Deseja excluir o tipo de terreiro?')
                    ->label(''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTypeTerreiros::route('/'),
        ];
    }
}
