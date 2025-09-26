<?php

namespace App\Filament\Admin\Resources\DeletedModels\Pages;

use App\Filament\Admin\Resources\DeletedModels\DeletedModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeletedModels extends ListRecords
{
    protected static string $resource = DeletedModelResource::class;
}
