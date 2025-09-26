<?php

namespace App\Filament\Admin\Resources\CommonQuestions\Pages;

use App\Filament\Admin\Resources\CommonQuestions\CommonQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommonQuestions extends ListRecords
{
    protected static string $resource = CommonQuestionResource::class;
}
