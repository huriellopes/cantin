<?php

namespace App\Services;

use App\Models\CommonQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CommonQuestionService
{
    /**
     * @return Collection
     */
    public function list() : Collection
    {
        return Cache::remember('common_questions', 60 * 60 * 24, function () {
            return CommonQuestion::query()
                ->active()
                ->get();
        });
    }
}
