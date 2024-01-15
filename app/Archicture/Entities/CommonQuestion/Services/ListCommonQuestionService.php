<?php

namespace App\Archicture\Entities\CommonQuestion\Services;

use App\Archicture\Entities\CommonQuestion\Interfaces\IListCommonQuestionService;
use App\Archicture\Entities\CommonQuestion\Models\CommonQuestion;
use App\Archicture\Entities\Status\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class ListCommonQuestionService implements IListCommonQuestionService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return CommonQuestion::query()
            ->with('status')
            ->where('status_id', '=', StatusEnum::ACTIVE->value)
            ->get();
    }
}
