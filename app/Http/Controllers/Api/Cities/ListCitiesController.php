<?php

namespace App\Http\Controllers\Api\Cities;

use App\Http\Controllers\Controller;
use App\Http\DTO\Cities\ListCitiesDTO;
use App\Http\Requests\Cities\ListCitiesRequest;
use App\Http\Resources\Cities\ListCitiesResourceCollection;
use App\Services\Cities\ListCitiesService;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListCitiesController extends Controller
{
    use Utils;

    /**
     * @param ListCitiesService $listCitiesService
     */
    public function __construct(
        protected ListCitiesService $listCitiesService
    ){}

    /**
     * @param ListCitiesRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function __invoke(ListCitiesRequest $request) : JsonResponse
    {
        try {
            $params = ListCitiesDTO::from($request);

            $cities = (new ListCitiesResourceCollection($this->listCitiesService->list($params)));

            return $this->returnResponse(
                true,
                null,
                $cities,
                Response::HTTP_OK,
                null,
                $cities->count()
            );
        } catch (Exception|\Throwable $e) {
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
