<?php

namespace App\Filament\Admin\Resources\CommonQuestions;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Filament\Admin\Resources\CommonQuestions\Pages\ListCommonQuestions;
use App\Enum\Status;
use App\Filament\Admin\Resources\CommonQuestionResource\Pages;
use App\Models\CommonQuestion;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use UnitEnum;

class CommonQuestionResource extends Resource
{
    protected static ?string $model = CommonQuestion::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $modelLabel = 'Perguntas Frequentes';

    protected static ?string $navigationLabel = 'Perguntas Frequentes';

    protected static ?string $breadcrumb = 'Perguntas Frequentes';

    protected static string | \UnitEnum | null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('question')
                            ->columnSpanFull()
                            ->required()
                            ->maxLength(255)
                            ->label(__('Question')),
                        Textarea::make('answer')
                            ->columnSpanFull()
                            ->required()
                            ->label(__('Answer')),
                    ])
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
                TextColumn::make('question')
                    ->label(__('Question')),
                TextColumn::make('status')
                    ->sortable()
                    ->badge()
                    ->color(fn (Status $state) => $state->getColor())
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->label('Status'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Status::ACTIVE->value => Status::ACTIVE->label(),
                        Status::INACTIVE->value => Status::INACTIVE->label(),
                    ])
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-m-plus')
                    ->tooltip('Criar uma Pergunta Frequente')
                    ->label('Criar uma Pergunta Frequente'),
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
                    ->schema([
                        TextInput::make('question')
                            ->columnSpanFull()
                            ->disabled()
                            ->label(__('Question')),
                        Textarea::make('answer')
                            ->columnSpanFull()
                            ->disabled()
                            ->label(__('Answer')),
                    ])
                    ->label(''),
                Action::make('active')
                    ->label('')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->tooltip('Ativar')
                    ->requiresConfirmation()
                    ->modalHeading('Ativar')
                    ->modalDescription('Voce tem certeza que deseja ativar essa pergunta?')
                    ->visible(fn (CommonQuestion $record) => $record->status == Status::INACTIVE)
                    ->action(function (CommonQuestion $record) {
                        $record->status = Status::ACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Ativado com sucesso!')
                            ->send();
                    }),
                Action::make('inactive')
                    ->label('')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->tooltip('Inativar')
                    ->requiresConfirmation()
                    ->modalHeading('Inativar')
                    ->modalDescription('Voce tem certeza que deseja inativar essa pergunta?')
                    ->visible(fn (CommonQuestion $record) => $record->status == Status::ACTIVE)
                    ->action(function (CommonQuestion $record) {
                        $record->status = Status::INACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Inativado com sucesso!')
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommonQuestions::route('/'),
        ];
    }
}
