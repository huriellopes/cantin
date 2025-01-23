<?php

namespace App\Http\Controllers\Api\Terreiros;

use App\Http\Controllers\Controller;
use App\Http\DTO\Terreiro\SearchTerreiroForUFDTO;
use App\Http\Requests\Terreiros\SearchTerreiroForUFRequest;
use App\Http\Resources\Terreiros\SearchResource;
use App\Services\Terreiros\SearchTerreiroForUFService;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SearchTerreiroForUfController extends Controller
{
    use Utils;

    /**
     * @param SearchTerreiroForUFService $searchTerreiroForUFService
     */
    public function __construct(
        protected SearchTerreiroForUFService $searchTerreiroForUFService,
    ){}

    /**
     * @param SearchTerreiroForUFRequest $request
     * @return JsonResponse
     */
    public function __invoke(SearchTerreiroForUFRequest $request) : JsonResponse
    {
        try {
            $params = SearchTerreiroForUFDTO::from($request);

            $terreiros = $this->searchTerreiroForUFService->search($params);

            if ($terreiros->isEmpty()) {
                return $this->returnResponse(
                    false,
                    Response::$statusTexts[Response::HTTP_NOT_FOUND],
                    null,
                    Response::HTTP_NOT_FOUND,
                );
            }

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_OK],
                SearchResource::collection($terreiros),
                Response::HTTP_OK,
                null,
                $terreiros->count(),
            );
        } catch (Exception|Throwable $e) {
            ds([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
            ])->danger();

            $this->loggingDatabase('Search Terreiro For UF', 'error', $e);

            return $this->returnResponse(
                false,
                Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                null,
                Response::HTTP_BAD_REQUEST,
                $e,
            );
        }
    }
}
