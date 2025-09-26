<?php

namespace App\Filament\Admin\Resources\Posts\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Posts\PostsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
