<?php

namespace App\Filament\Admin\Resources\Categories;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\Categories\Pages\ListCategories;
use App\Enum\Status;
use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
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
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Categorias';

    protected static ?string $pluralLabel = 'Categorias';

    protected static ?string $modelLabel = 'Categoria';

    protected static string | \UnitEnum | null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->string()
                            ->label(__('Name')),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'O campo :attribute é obrigatório.',
                                'unique' => 'O registro já existe.'
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
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('#'),
                TextColumn::make('name')
                    ->searchable()
                    ->label(__('Name')),
                TextColumn::make('slug')
                    ->searchable()
                    ->label('Slug'),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->getColor()),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->label(__('Created At')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-m-plus')
                    ->label('Criar Categoria')
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
                    ->icon('heroicon-m-pencil-square')
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->label(''),
                ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->label(''),
                Action::make('active')
                    ->label('')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Ativar')
                    ->modalDescription('Tem certeza que deseja ativar?')
                    ->tooltip('Ativar')
                    ->visible(fn (Category $record) => $record->status == Status::INACTIVE)
                    ->action(function (Category $record) {
                        $record->status = Status::ACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Inativado com sucesso!')
                            ->send();
                    }),
                Action::make('inactive')
                    ->label('')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->tooltip('Inativar')
                    ->visible(fn (Category $record) => $record->status == Status::ACTIVE)
                    ->action(function (Category $record) {
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
            'index' => ListCategories::route('/'),
        ];
    }
}
