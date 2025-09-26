<?php

namespace App\Filament\Admin\Resources\DeletedModels;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Filament\Admin\Resources\DeletedModels\Pages\ListDeletedModels;
use App\Filament\Admin\Resources\DeletedModels\Pages\EditDeletedModel;
use App\Filament\Admin\Resources\DeletedModelResource\Pages;
use App\Models\DeletedModel;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class DeletedModelResource extends Resource
{
    protected static ?string $model = DeletedModel::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-trash';

    protected static ?string $navigationLabel = 'Excluídos';

    protected static ?string $breadcrumb = 'Excluídos';

    protected static ?string $modelLabel = 'Excluídos';

    protected static string | \UnitEnum | null $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 5;

    public static function canAccess() : bool
    {
        return auth()->user()->hasRole('super-admin');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                TextColumn::make('key')
                    ->sortable()
                    ->label('Chave'),
                TextColumn::make('model')
                    ->sortable()
                    ->label('Modelo'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label(__('Created at')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make('view_deleted')
                    ->schema([
                        TextInput::make('model')
                            ->label('Modelo'),
                        Textarea::make('values')
                            ->label('Conteúdo')
                            ->rows(10)
                            ->columnSpanFull()
                            ->afterStateHydrated(function (Textarea $component, ?Model $record) {
                                if ($record && $record->values) {
                                    $component->state(json_encode($record->values, JSON_PRETTY_PRINT));
                                }
                            })
                            ->dehydrateStateUsing(function ($state) {
                                return json_decode($state, true);
                            }),
                    ])
                    ->size('10')
                    ->color('info')
                    ->icon('heroicon-o-eye')
                    ->label('')
                    ->tooltip(__('View'))
                    ->modalHeading(fn ($record) => "View key {$record->key}")
                    ->modalWidth('2xl')
                    ->slideOver(),
                Action::make('restore')
                    ->size('10')
                    ->label('')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->tooltip(__('Restore'))
                    ->requiresConfirmation()
                    ->action(function (DeletedModel $record) {
                        (new $record->model)->restore($record->key);

                        Notification::make()
                            ->success()
                            ->title('Restaurado com sucesso')
                            ->send();
                    }),
                Action::make('delete')
                    ->size('10')
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Excluir')
                    ->modalHeading('Excluir permanentemente')
                    ->modalDescription('Tem certeza que deseja excluir permanentemente?')
                    ->requiresConfirmation()
                    ->action(function (DeletedModel $record) {
                        $record->forceDelete();

                        Notification::make()
                            ->success()
                            ->title('Registro excluído permanentemente com sucesso')
                            ->send();
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeletedModels::route('/'),
            'edit' => EditDeletedModel::route('/{record}/edit'),
        ];
    }
}
