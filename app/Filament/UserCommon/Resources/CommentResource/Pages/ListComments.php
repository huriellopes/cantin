<?php

namespace App\Filament\UserCommon\Resources\CommentResource\Pages;

use App\Filament\UserCommon\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;
}
