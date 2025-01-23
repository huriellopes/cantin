<?php

namespace App\Filament\Admin\Resources;

use App\Models\MenuSite;
use App\Enums\Status;
use App\Filament\Admin\Resources\MenuSiteResource\Pages;
use App\Filament\Admin\Resources\MenuSiteResource\RelationManagers;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuSiteResource extends Resource
{
    protected static ?string $model = MenuSite::class;

    protected static ?string $navigationLabel = 'Menus';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $breadcrumb = 'Menus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(200)
                    ->string()
                    ->label('Nome'),
                Forms\Components\TextInput::make('route')
                    ->required()
                    ->label('Rota'),
                Forms\Components\TextInput::make('description')
                    ->nullable()
                    ->string()
                    ->columnSpan(2)
                    ->label('Descrição'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('route')
                    ->label('Rota')
                    ->formatStateUsing(fn ($state) => $state === null ? 'Nenhuma' : "/{$state}")
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) : string => $state->label())
                    ->badge()
                    ->color(fn ($state): string => match ($state->value) {
                        1 => 'success',
                        0 => 'danger',
                    })
                    ->label('Status'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Criado por')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Ativar')
                    ->action(function (MenuSite $menu) {
                        $menu->status = Status::ACTIVE;
                        $menu->deleted_at = null;
                        $menu->save();
                    })
                    ->hidden(fn (MenuSite $menu) : bool => $menu->status->value),
                Tables\Actions\Action::make('Inativar')
                    ->action(function (MenuSite $menu) {
                        $menu->status = Status::INACTIVE;
                        $menu->deleted_at = Carbon::now();
                        $menu->save();
                    })
                    ->visible(fn (MenuSite $menu) : bool => $menu->status->value),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuSites::route('/'),
            'create' => Pages\CreateMenuSite::route('/create'),
            'edit' => Pages\EditMenuSite::route('/{record}/edit'),
        ];
    }
}
