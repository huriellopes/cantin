<?php

namespace App\Http\Controllers\Api\TypePeople;

use App\Http\Controllers\Controller;
use App\Services\TypePeoples\ListTypePeopleService;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;

class ListTypePeopleController extends Controller
{
    use Utils;

    /**
     * @param ListTypePeopleService $listTypePeopleService
     */
    public function __construct(
        protected ListTypePeopleService $listTypePeopleService,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $listTypePeople = $this->listTypePeopleService->list();

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_OK],
                $listTypePeople,
                Response::HTTP_OK,
                null,
                $listTypePeople->count(),
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
                $e,
            );
        }
    }
}
