<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CommonQuestionResource\Pages;
use App\Filament\Admin\Resources\CommonQuestionResource\RelationManagers;
use App\Models\CommonQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommonQuestionResource extends Resource
{
    protected static ?string $model = CommonQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

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
                Tables\Columns\TextColumn::make('id')
                    ->label('#'),
                Tables\Columns\TextColumn::make('question')
                    ->label(__('Question')),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
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
            'index' => Pages\ListCommonQuestions::route('/'),
            'create' => Pages\CreateCommonQuestion::route('/create'),
            'edit' => Pages\EditCommonQuestion::route('/{record}/edit'),
        ];
    }
}
