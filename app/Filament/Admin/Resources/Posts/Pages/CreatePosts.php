<?php

namespace App\Filament\Admin\Resources\Posts\Pages;

use App\Enum\StatusPost;
use App\Filament\Admin\Resources\Posts\PostsResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Models\Contracts\FilamentUser;
use Filament\Resources\Pages\CreateRecord;

class CreatePosts extends CreateRecord
{
    protected static string $resource = PostsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        $data['status'] = $data['published_at'] === Carbon::now()->format('Y-m-d')
            ? StatusPost::PUBLISHED : StatusPost::PENDING;

        return $data;
    }
}
