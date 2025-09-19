<?php

namespace App\Filament\Admin\Resources\Pages\Tables;

use App\Enum\Status;
use App\Models\Page;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state->label())
                    ->label('Status'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalWidth(Width::FiveExtraLarge)
                    ->icon('heroicon-m-plus')
                    ->label('Criar Página')
                    ->extraModalFooterActions([
                        Action::make('cancel')
                            ->label(''),
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Status::ACTIVE->value => Status::ACTIVE->label(),
                        Status::INACTIVE->value => Status::INACTIVE->label(),
                    ])
                    ->label('Status'),
                Filter::make('created_at')
                    ->label('Intervalo de Datas')
                    ->schema([
                        DatePicker::make('from')
                            ->maxDate(Carbon::now())
                            ->label('De'),
                        DatePicker::make('to')
                            ->maxDate(Carbon::now())
                            ->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['to'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
//            ->filtersFormWidth(Width::FourExtraLarge)
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Editar')
                    ->color('primary'),
                DeleteAction::make()
                    ->tooltip('Excluir')
                    ->icon('heroicon-m-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Excluir')
                    ->modalDescription('Tem certeza que deseja excluir esse registro?')
                    ->label(''),
                Action::make('active')
                    ->tooltip('Ativar')
                    ->color('success')
                    ->label('')
                    ->icon('heroicon-m-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Editar')
                    ->modalDescription('Tem certeza que deseja ativar este registro?')
                    ->visible(fn(Page $record) => $record->status === Status::INACTIVE)
                    ->action(function (Page $record) {
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
                    ->visible(fn(Page $record) => $record->status === Status::ACTIVE)
                    ->action(function (Page $record) {
                        $record->status = Status::INACTIVE;
                        $record->save();

                        Notification::make()
                            ->title('Registro desativado com sucesso!')
                            ->success()
                            ->send();
                    }),
                ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip('Visualizar')
                    ->color('primary'),
            ]);
    }
}
