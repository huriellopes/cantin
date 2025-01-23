<?php

namespace App\Services\CommonQuestion;

use App\Models\CommonQuestion;
use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class ListCommonQuestionService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return CommonQuestion::query()
            ->select('id', 'answer','question', 'status_id', 'created_at')
            ->with('status:id,name')
            ->where('status_id', '=', StatusEnum::ACTIVE->value)
            ->get();
    }
}
