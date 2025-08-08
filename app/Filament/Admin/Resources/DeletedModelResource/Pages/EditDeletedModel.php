<?php

namespace App\Filament\Admin\Resources\DeletedModelResource\Pages;

use App\Filament\Admin\Resources\DeletedModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeletedModel extends EditRecord
{
    protected static string $resource = DeletedModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
