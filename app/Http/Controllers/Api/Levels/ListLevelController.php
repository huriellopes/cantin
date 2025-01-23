<?php

namespace App\Http\Controllers\Api\Levels;

use App\Http\Controllers\Controller;
use App\Services\Levels\ListLevelService;
use App\Traits\Utils;
use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListLevelController extends Controller
{
    use Utils;

    /**
     * @param ListLevelService $listLevelService
     */
    public function __construct(
        protected ListLevelService $listLevelService,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $levels = $this->listLevelService->listLevels();

            return $this->returnResponse(
                true,
                null,
                $levels,
                Response::HTTP_OK,
                null,
                $levels->count()
            );
        } catch(Exception|Throwable $e) {
            ds([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
            ])->danger();
            return $this->returnResponse(
                false,
                Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                null,
                Response::HTTP_BAD_REQUEST,
                $e
            );
        }
    }
}
