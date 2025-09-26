<?php

namespace App\Filament\Admin\Resources\DeletedModels\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\DeletedModels\DeletedModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeletedModel extends EditRecord
{
    protected static string $resource = DeletedModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
