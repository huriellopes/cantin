<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ExternalLinkResource\Pages;
use App\Filament\Admin\Resources\ExternalLinkResource\RelationManagers;
use App\Models\ExternalLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExternalLinkResource extends Resource
{
    protected static ?string $model = ExternalLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->columnSpanFull()
                            ->required()
                            ->label('Titulo'),
                        Forms\Components\TextInput::make('url')
                            ->columnSpanFull()
                            ->required()
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
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListExternalLinks::route('/'),
            'create' => Pages\CreateExternalLink::route('/create'),
            'edit' => Pages\EditExternalLink::route('/{record}/edit'),
        ];
    }
}
