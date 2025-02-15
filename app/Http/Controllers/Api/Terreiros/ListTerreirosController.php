<?php

namespace App\Http\Controllers\Api\Terreiros;

use App\Http\Controllers\Controller;
use App\Services\Terreiros\ListTerreirosService;
use App\Traits\MessagesDefaults;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListTerreirosController extends Controller
{
    use Utils;

    /**
     * @param ListTerreirosService $listTerreirosService
     */
    public function __construct(
        protected ListTerreirosService $listTerreirosService,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $terreiros = $this->listTerreirosService->list();

            return $this->returnResponse(
                true,
                null,
                $terreiros,
                Response::HTTP_OK,
                null,
                $terreiros->count()
            );
        } catch (Exception $exception) {
            return $this->returnResponse(
                false,
                MessagesDefaults::ERROR400,
                null,
                Response::HTTP_BAD_REQUEST,
                $exception
            );
        }
    }
}
