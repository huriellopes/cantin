<?php

namespace App\Filament\Admin\Resources\Posts\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Posts\PostsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosts extends EditRecord
{
    protected static string $resource = PostsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
