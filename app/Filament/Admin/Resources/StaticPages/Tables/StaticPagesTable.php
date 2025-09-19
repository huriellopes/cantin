<?php

namespace App\Filament\Admin\Resources\StaticPages\Tables;

use App\Enum\Status;
use App\Models\StaticPage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaticPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),
                TextColumn::make('slug')
                    ->searchable()
                    ->label('Slug'),
                TextColumn::make('user.name')
                    ->sortable()
                    ->label('Criado por'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (Status $state) => $state->getColor())
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable()
                    ->searchable()
                    ->label('Status'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Criado em'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-m-plus')
                    ->label('Criar Página Estática')
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
                    ->tooltip('Editar')
                    ->icon('heroicon-m-pencil-square')
                    ->label(''),
                DeleteAction::make()
                    ->tooltip('Excluir')
                    ->icon('heroicon-m-trash')
                    ->label(''),
                Action::make('active')
                    ->tooltip('Ativar')
                    ->color('success')
                    ->label('')
                    ->icon('heroicon-m-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Editar')
                    ->modalDescription('Tem certeza que deseja ativar este registro?')
                    ->visible(fn (StaticPage $record) => $record->status === Status::INACTIVE)
                    ->action(function (StaticPage $record) {
                        $record->status = Status::ACTIVE;
                        $record->save();

                        Notification::make()
                            ->title('Registro ativado com sucesso!')
                            ->success()
                            ->send();
                    }),
                Action::make('inactive')
                    ->label('')
                    ->color('danger')
                    ->tooltip('Desativar')
                    ->icon('heroicon-m-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Editar')
                    ->modalDescription('Tem certeza que deseja desativar este registro?')
                    ->visible(fn (StaticPage $record) => $record->status === Status::ACTIVE)
                    ->action(function (StaticPage $record) {
                        $record->status = Status::INACTIVE;
                        $record->save();

                        Notification::make()
                            ->title('Registro desativado com sucesso!')
                            ->success()
                            ->send();
                    }),
                ViewAction::make()
                    ->tooltip('Visualizar')
                    ->icon('heroicon-m-eye')
                    ->label(''),
            ]);
    }
}
