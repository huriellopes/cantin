<?php

namespace App\Filament\Admin\Resources\ExternalLinks\Pages;

use App\Filament\Admin\Resources\ExternalLinks\ExternalLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalLinks extends ListRecords
{
    protected static string $resource = ExternalLinkResource::class;
}
