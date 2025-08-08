<?php

namespace App\Http\Controllers\Api\CommonQuestions;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommonResource;
use App\Services\CommonQuestionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommonQuestionController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function __invoke() : AnonymousResourceCollection
    {
        $common = app(CommonQuestionService::class)->list();

        return CommonResource::collection($common);
    }
}
