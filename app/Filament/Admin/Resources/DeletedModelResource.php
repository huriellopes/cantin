<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DeletedModelResource\Pages;
use App\Filament\Admin\Resources\DeletedModelResource\RelationManagers;
use App\Models\DeletedModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeletedModelResource extends Resource
{
    protected static ?string $model = DeletedModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';

    protected static ?string $navigationLabel = 'Excluídos';

    protected static ?string $breadcrumb = 'Excluídos';

    protected static ?string $modelLabel = 'Excluídos';

    protected static ?string $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            ->bulkActions([]);
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
            'index' => Pages\ListDeletedModels::route('/'),
            'edit' => Pages\EditDeletedModel::route('/{record}/edit'),
        ];
    }
}
