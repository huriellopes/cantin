<?php

namespace App\Http\Controllers\Api\Partners;

use App\Http\Controllers\Controller;
use App\Http\Resources\Partners\PartnersResource;
use App\Services\Partners\ListPartnersService;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Exception;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class ListPartnersController extends Controller
{
    use Utils;

    /**
     * @param ListPartnersService $listPartnersService
     */
    public function __construct(
        protected ListPartnersService $listPartnersService,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $list = PartnersResource::collection($this->listPartnersService->list());

            if ($list->isEmpty()) {
                return $this->returnResponse(
                    false,
                    Response::$statusTexts[Response::HTTP_NOT_FOUND],
                    null,
                    Response::HTTP_NOT_FOUND,
                );
            }

            return $this->returnResponse(
                true,
                null,
                $list,
                Response::HTTP_OK,
                null,
                $list->count(),
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
