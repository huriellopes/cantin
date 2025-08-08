<?php

namespace App\Filament\Admin\Resources\ExternalLinkResource\Pages;

use App\Filament\Admin\Resources\ExternalLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalLinks extends ListRecords
{
    protected static string $resource = ExternalLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
