<?php

namespace App\Filament\Admin\Resources;

use App\Enum\Status;
use App\Filament\Admin\Resources\CommentResource\Pages;
use App\Models\Comment;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Comentarios';

    protected static ?string $pluralLabel = 'Comentarios';

    protected static ?string $modelLabel = 'Comentário';

    protected static string | UnitEnum | null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereNull('parent_id');
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->formatStateUsing(fn ($state) => $state ?? '---'),
                Tables\Columns\TextColumn::make('post.title')
                    ->label('Post'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('link_post')
                    ->icon('heroicon-m-link')
                    ->tooltip('Visitar post')
                    ->label('')
                    ->url(function (Comment $record) {
                        $post = Post::query()
                            ->select('slug')
                            ->where('id', '=', $record->post_id)
                            ->first();

                        if (!$post) {
                            return '#';
                        }

                        return route('site.blog.show', ['post' => $post->slug]);
                    })
                    ->openUrlInNewTab(),
                Action::make('reply')
                    ->icon('heroicon-m-arrow-path')
                    ->label('')
                    ->tooltip('Responder')
                    ->modalHeading('Responder')
                    ->schema([
                        Forms\Components\Hidden::make('parent_id')
                            ->default(fn (Comment $comment) => $comment->id),

                        Forms\Components\Textarea::make('original_comment_body')
                            ->label('Comentário do usuário')
                            ->formatStateUsing(fn (Comment $comment) => $comment->body)
                            ->columnSpanFull()
                            ->hint('Leia o comentário, antes de responder.')
                            ->hintColor('danger')
                            ->disabled(),

                        Forms\Components\Textarea::make('body')
                            ->label('Sua Resposta')
                            ->required()
                            ->minLength(1)
                            ->maxLength(500)
                            ->placeholder('Digite sua resposta aqui...'),
                    ])
                    ->action(function (array $data, Comment $comment) {
                        Comment::query()->create([
                            'body' => $data['body'],
                            'ip_address' => request()->ip(),
                            'user_id' => Auth::user()->id,
                            'parent_id' => $data['parent_id'],
                            'post_id' => $comment->post_id,
                        ]);

                        Notification::make()
                            ->title('Resposta enviada com sucesso!')
                            ->success()
                            ->send();
                    }),
                Action::make('disable')
                    ->label('')
                    ->color('danger')
                    ->icon('heroicon-m-x-circle')
                    ->tooltip('Desativar')
                    ->modalHeading('Desativar')
                    ->modalDescription('Tem certeza que deseja desativar comentário?')
                    ->requiresConfirmation()
                    ->action(function (Comment $record) {
                        $record->status = Status::INACTIVE;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title('Comentário desativado com sucesso!')
                            ->send();
                    }),
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
            'index' => Pages\ListComments::route('/'),
        ];
    }
}
