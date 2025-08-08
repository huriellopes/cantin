<?php

namespace App\Filament\Admin\Resources;

use App\Enum\StatusPost;
use App\Filament\Admin\Resources\PostsResource\Pages;
use App\Filament\Admin\Resources\PostsResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make()
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
                Tables\Columns\TextColumn::make('likes')
                    ->label('Likes'),
                Tables\Columns\TextColumn::make('views')
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->label(''),
                Tables\Actions\Action::make('publish')
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
                    ->icon('heroicon-m-check-circle')
                    ->label('')
                    ->visible(fn (Post $post) => $post->status === StatusPost::PENDING),

                Tables\Actions\Action::make('not_publish')
                    ->action(function (Post $post) {
                        if (Carbon::parse($post->published_at)->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                            return Notification::make()
                                ->warning()
                                ->title('Ainda não é possível publicar')
                                ->send();
                        }
                        $post->status = StatusPost::PENDING;
                        $post->save();
                    })
                    ->tooltip(__('Not Publish'))
                    ->requiresConfirmation()
                    ->icon('heroicon-m-x-circle')
                    ->label('')
                    ->visible(fn (Post $post) => $post->status === StatusPost::PUBLISHED),

                Tables\Actions\Action::make('link')
                    ->tooltip(__('View Post in web'))
                    ->icon('heroicon-m-link')
                    ->label('')
                    ->url(fn (Post $post) => route('site.blog.show', ['post' => $post->slug]))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePosts::route('/create'),
            'edit' => Pages\EditPosts::route('/{record}/edit'),
        ];
    }
}
