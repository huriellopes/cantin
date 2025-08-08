<?php

namespace App\Filament\UserCommon\Resources\CommentResource\Pages;

use App\Filament\UserCommon\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}
