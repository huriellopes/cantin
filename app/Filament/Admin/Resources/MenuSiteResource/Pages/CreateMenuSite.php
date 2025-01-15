<?php

namespace App\Filament\Admin\Resources\MenuSiteResource\Pages;

use App\Enums\Status;
use App\Filament\Admin\Resources\MenuSiteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuSite extends CreateRecord
{
    protected static string $resource = MenuSiteResource::class;

    protected static ?string $title = 'Novo Menu';
    protected static ?string $navigationLabel = 'Novo Menu';

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return Actions\Action::make('createAnother')
            ->hidden();
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $params = (object) $data;

        $menu = new \App\Archicture\Entities\MenusSites\Models\MenuSite();

        $menu->name = $params->name;
        $menu->route = $params->route;
        $menu->description = $params->description;
        $menu->user_id = auth()->id();
        $menu->status = Status::INACTIVE;

        $menu->save();

        return $menu;
    }
}
