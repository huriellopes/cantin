<?php

namespace App\Filament\Admin\Resources\DeletedModelResource\Pages;

use App\Filament\Admin\Resources\DeletedModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeletedModels extends ListRecords
{
    protected static string $resource = DeletedModelResource::class;
}
