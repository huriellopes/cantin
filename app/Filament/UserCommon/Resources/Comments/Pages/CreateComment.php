<?php

namespace App\Filament\UserCommon\Resources\Comments\Pages;

use App\Filament\UserCommon\Resources\Comments\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;
}
