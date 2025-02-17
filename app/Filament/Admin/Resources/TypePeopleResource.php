<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TypePeopleResource\Pages;
use App\Filament\Admin\Resources\TypePeopleResource\RelationManagers;
use App\Models\TypePeople;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypePeopleResource extends Resource
{
    protected static ?string $model = TypePeople::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->string()
                    ->label('Nome'),
                Forms\Components\TextInput::make('description')
                    ->string()
                    ->label('Descrição'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nome'),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable()
                    ->dateTime('d/m/Y')
                    ->label('Criado em'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListTypePeople::route('/'),
            'create' => Pages\CreateTypePeople::route('/create'),
            'edit' => Pages\EditTypePeople::route('/{record}/edit'),
        ];
    }
}
