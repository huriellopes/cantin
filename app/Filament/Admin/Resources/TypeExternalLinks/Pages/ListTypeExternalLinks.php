<?php

namespace App\Filament\Admin\Resources\TypeExternalLinks\Pages;

use App\Filament\Admin\Resources\TypeExternalLinks\TypeExternalLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTypeExternalLinks extends ListRecords
{
    protected static string $resource = TypeExternalLinkResource::class;
}
