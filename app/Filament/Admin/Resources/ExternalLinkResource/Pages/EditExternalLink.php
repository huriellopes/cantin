<?php

namespace App\Filament\Admin\Resources\ExternalLinkResource\Pages;

use App\Filament\Admin\Resources\ExternalLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExternalLink extends EditRecord
{
    protected static string $resource = ExternalLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
