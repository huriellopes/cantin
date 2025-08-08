<?php

namespace App\Filament\Admin\Resources\CommonQuestionResource\Pages;

use App\Filament\Admin\Resources\CommonQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommonQuestions extends ListRecords
{
    protected static string $resource = CommonQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Criar Pergunta Frequente'),
        ];
    }
}
