<?php

namespace App\Filament\Admin\Resources;

use App\Enum\Status;
use App\Filament\Admin\Resources\TypeExternalLinkResource\Pages\ListTypeExternalLinks;
use App\Filament\Resources\TypeExternalLinkResource\Pages;
use App\Models\TypeExternalLink;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class TypeExternalLinkResource extends Resource
{
    protected static ?string $model = TypeExternalLink::class;

    protected static ?string $slug = 'type-external-links';

    protected static ?string $navigationLabel = 'Tipos de Link';

    protected static ?string $pluralLabel = 'Tipos de Link';

    protected static ?string $modelLabel = 'Tipo de Link';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::LinkSlash;

    protected static string | UnitEnum | null $navigationGroup = 'Site';

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

                        TextInput::make('slug')
                            ->unique()
                            ->required()
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                                'unique' => 'O slug do tipo de link já existe. Tente novamente!'
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
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->sortable()
                    ->badge()
                    ->color(fn (Status $state) => $state->getColor())
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->label('Status'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Criar Tipo de Link')
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
                    ->label('')
                    ->tooltip('Editar'),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->label('')
                    ->tooltip('Excluir')
                    ->modalHeading('Excluir Tipo de Link')
                    ->modalDescription('Tem certeza que deseja excluir este tipo de link?')
                    ->requiresConfirmation(),
                Action::make('enable')
                    ->icon('heroicon-m-check-circle')
                    ->label('')
                    ->tooltip('Ativar')
                    ->requiresConfirmation()
                    ->color('success')
                    ->modalHeading('Ativar Tipo de Link')
                    ->modalDescription('Tem certeza que deseja ativar este tipo de link?')
                    ->visible(fn (TypeExternalLink $record) => $record->status === Status::INACTIVE)
                    ->action(function (TypeExternalLink $record) {
                        if ($record->status === Status::ACTIVE) {
                            Notification::make()
                                ->warning()
                                ->title('O tipo de link já está ativo!')
                                ->send();
                            return;
                        }

                        $record->status = Status::ACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Tipo de link ativado com sucesso!')
                            ->send();
                    }),
                Action::make('disable')
                    ->icon('heroicon-m-x-circle')
                    ->label('')
                    ->tooltip('Desativar')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Desativar Tipo de Link')
                    ->modalDescription('Tem certeza que deseja desativar este tipo de link?')
                    ->visible(fn (TypeExternalLink $record) => $record->status === Status::ACTIVE)
                    ->action(function (TypeExternalLink $record) {
                        if ($record->status === Status::INACTIVE) {
                            Notification::make()
                                ->warning()
                                ->title('O tipo de link já está inativo!')
                                ->send();
                            return;
                        }

                        $record->status = Status::INACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Tipo de link desativado com sucesso!')
                            ->send();
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTypeExternalLinks::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }
}
