<?php

namespace App\Filament\Admin\Resources\MenuSiteResource\Pages;

use App\Filament\Admin\Resources\MenuSiteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenuSite extends EditRecord
{
    protected static string $resource = MenuSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
