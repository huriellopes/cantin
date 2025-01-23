<?php

namespace App\Http\Controllers\Api\States;

use App\Http\Controllers\Controller;
use App\Http\Resources\States\ListStatesResourceCollection;
use App\Services\States\ListStatesService;
use App\Traits\Utils;
use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListStatesController extends Controller
{
    use Utils;

    /**
     * @param ListStatesService $listStatesService
     */
    public function __construct(
        protected ListStatesService $listStatesService
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $states = (new ListStatesResourceCollection($this->listStatesService->list()));

            return $this->returnResponse(
                true,
                null,
                $states,
                Response::HTTP_OK,
                null,
                $states->count(),
            );

        } catch (Exception|Throwable $e) {
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
