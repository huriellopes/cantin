<?php

namespace App\Filament\Admin\Resources;

use App\Enum\StatusPost;
use App\Filament\Admin\Resources\PostsResource\Pages;
use App\Models\Category;
use App\Models\Post;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class PostsResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Posts';

    protected static ?string $pluralLabel = 'Posts';

    protected static ?string $modelLabel = 'Post';

    protected static string | UnitEnum | null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpanFull()
                            ->label(__('Title')),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->label(__('Slug')),
                        Forms\Components\DatePicker::make('published_at')
                            ->required()
                            ->label(__('Published at')),
                        Forms\Components\Select::make('category_id')
                            ->options(Category::query()->pluck('name', 'id'))
                            ->required()
                            ->label(__('Categories')),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDisk('public')
                            ->label(__('Content')),
                        Forms\Components\FileUpload::make('main_image')
                            ->label(__('Imagem Principal'))
                            ->image()
                            ->disk('public')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title')),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('Slug')),
                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('Published at'))
                    ->dateTime('d/m/Y'),
                Tables\Columns\TextColumn::make('likes_count')
                    ->badge()
                    ->counts('likes')
                    ->label(__('Likes')),
                Tables\Columns\TextColumn::make('views')
                    ->badge()
                    ->color('success')
                    ->label('Views'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Author')),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->getColor())
                    ->badge()
                    ->label(__('Status')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->label(__('Created at'))
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip(__('Edit'))
                    ->label(''),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->tooltip(__('Delete'))
                    ->requiresConfirmation()
                    ->modalHeading('Excluir o post')
                    ->modalDescription('Você tem certeza que deseja excluir o post?')
                    ->modalSubmitActionLabel(__('Delete'))
                    ->color('danger')
                    ->visible(fn (Post $post) => $post->status === StatusPost::PENDING)
                    ->label(''),
                Action::make('publish')
                    ->action(function (Post $post) {
                        if (Carbon::parse($post->published_at)->format('Y-m-d') <= Carbon::now()->format('Y-m-d')) {
                            $post->status = StatusPost::PUBLISHED;
                            $post->save();

                            return Notification::make()
                                ->success()
                                ->title('Publicado com sucesso')
                                ->send();
                        }

                        return Notification::make()
                            ->warning()
                            ->title('Ainda não é possível publicar')
                            ->send();
                    })
                    ->tooltip(__('Publish'))
                    ->requiresConfirmation()
                    ->modalHeading('Publicar o post')
                    ->modalDescription('Tem certeza que deseja publicar o post?')
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->label('')
                    ->visible(fn (Post $post) => $post->status === StatusPost::PENDING),

                Action::make('not_publish')
                    ->action(function (Post $post) {
                        if (Carbon::parse($post->published_at)->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                            return Notification::make()
                                ->warning()
                                ->title('Ainda não é possível publicar')
                                ->send();
                        }
                        $post->status = StatusPost::PENDING;
                        $post->save();

                        Notification::make()
                            ->success()
                            ->title('Não Publicado com sucesso')
                            ->send();
                    })
                    ->tooltip(__('Not Publish'))
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Despublicar o post')
                    ->modalDescription('Tem certeza que deseja des-publicar o post?')
                    ->icon('heroicon-m-x-circle')
                    ->label('')
                    ->visible(fn (Post $post) => $post->status === StatusPost::PUBLISHED),

                Action::make('link')
                    ->tooltip(__('View Post in web'))
                    ->icon('heroicon-m-link')
                    ->label('')
                    ->url(fn (Post $post) => route('site.blog.show', ['post' => $post->slug]))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePosts::route('/create'),
            'edit' => Pages\EditPosts::route('/{record}/edit'),
        ];
    }
}
