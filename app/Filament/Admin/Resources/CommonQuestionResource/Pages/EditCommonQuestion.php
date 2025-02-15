<?php

namespace App\Filament\Admin\Resources\CommonQuestionResource\Pages;

use App\Filament\Admin\Resources\CommonQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommonQuestion extends EditRecord
{
    protected static string $resource = CommonQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
