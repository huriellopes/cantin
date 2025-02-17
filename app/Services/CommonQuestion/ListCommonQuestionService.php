<?php

namespace App\Services\CommonQuestion;

use App\Models\CommonQuestion;
use App\Enum\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ListCommonQuestionService
{
    /**
     * @return Collection
     */
    public static function list(): Collection
    {
        return Cache::remember('commons', 600,function () {
            return CommonQuestion::query()
                ->select('id', 'answer','question', 'status', 'created_at')
                ->where('status', '=', Status::ACTIVE)
                ->get();
        });
    }
}
