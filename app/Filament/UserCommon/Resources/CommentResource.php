<?php

namespace App\Filament\UserCommon\Resources;

use App\Filament\UserCommon\Resources\CommentResource\Pages;
use App\Filament\UserCommon\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $modelLabel = 'Comentários';

    protected static ?string $navigationLabel = 'Comentários';

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', '=', auth()->user()->id)->with('replies');
            })
            ->columns([
                Tables\Columns\TextColumn::make('body')
                    ->sortable()
                    ->searchable()
                    ->label('Comentario'),
                Tables\Columns\TextColumn::make('replies')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Sim' : 'Não')
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->sortable()
                    ->searchable()
                    ->label('Resposta'),
                Tables\Columns\TextColumn::make('post.title')
                    ->sortable()
                    ->searchable()
                    ->label('Post'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view_comment_post')
                    ->label('')
                    ->url(fn (Comment $record) => route('site.blog.show', $record->post->slug))
                    ->openUrlInNewTab()
                    ->tooltip('Ver Comentario')
                    ->icon('heroicon-o-eye'),
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
            'index' => Pages\ListComments::route('/'),
        ];
    }
}
