<?php

namespace App\Filament\Admin\Resources;

use App\Enum\Status;
use App\Filament\Admin\Resources\ExternalLinkResource\Pages;
use App\Filament\Admin\Resources\ExternalLinkResource\RelationManagers;
use App\Models\ExternalLink;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ExternalLinkResource extends Resource
{
    protected static ?string $model = ExternalLink::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-c-link';

    protected static ?string $navigationLabel = 'Links Externos';

    protected static ?string $pluralLabel = 'Links Externos';

    protected static ?string $modelLabel = 'Link Externo';

    protected static string | UnitEnum | null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->columnSpanFull()
                            ->required()
                            ->label('Titulo'),
                        Forms\Components\TextInput::make('slug')
                            ->columnSpanFull()
                            ->required()
                            ->unique()
                            ->label('Slug'),
                        Forms\Components\Select::make('type_external_link_id')
                            ->relationship('typeExternalLink', 'name')
                            ->searchable()
                            ->columnSpanFull()
                            ->preload()
                            ->label('Tipo do link'),
                        Forms\Components\TextInput::make('url')
                            ->columnSpanFull()
                            ->required()
                            ->prefixIcon(Heroicon::GlobeAlt)
                            ->label('URL'),
                        Forms\Components\TextInput::make('description')
                            ->columnSpanFull()
                            ->required()
                            ->label('Descrição'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Titulo'),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL'),
                Tables\Columns\TextColumn::make('typeExternalLink.name')
                    ->sortable()
                    ->searchable()
                    ->label('Tipo do Link'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->label('Status'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-m-plus')
                    ->label('Criar novo link')
                    ->extraModalFooterActions([
                        Action::make('cancel')
                            ->label(''),
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
                    ->label(''),
                ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->tooltip('Visualizar')
                    ->label(''),
                Action::make('active')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->label('')
                    ->tooltip('Ativar o link')
                    ->visible(fn ($record) => $record->status === Status::INACTIVE)
                    ->action(function ($record) {
                        $record->status = Status::ACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Link ativado com sucesso!')
                            ->send();
                    }),
                Action::make('inactive')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->label('')
                    ->tooltip('Inativar o link')
                    ->visible(fn ($record) => $record->status === Status::ACTIVE)
                    ->action(function ($record) {
                        $record->status = Status::INACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Link inativado com sucesso!')
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExternalLinks::route('/'),
        ];
    }
}
