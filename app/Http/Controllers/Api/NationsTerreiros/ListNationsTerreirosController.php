<?php

namespace App\Http\Controllers\Api\NationsTerreiros;

use App\Http\Controllers\Controller;
use App\Services\NationsTerreiros\ListNationsTerreirosService;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;

class ListNationsTerreirosController extends Controller
{
    use Utils;

    /**
     * @param ListNationsTerreirosService $listNationsTerreirosService
     */
    public function __construct(
        protected ListNationsTerreirosService $listNationsTerreirosService,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $list = $this->listNationsTerreirosService->list();

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
