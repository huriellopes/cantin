<?php

namespace App\Http\Controllers\Api\NationsTerreiros;

use App\Archicture\Entities\NationsTerreiros\Actions\ListNationsTerreirosAction;
use App\Http\Controllers\Controller;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;

class ListNationsTerreirosController extends Controller
{
    use Utils;

    /**
     * @param ListNationsTerreirosAction $listNationsTerreirosAction
     */
    public function __construct(
        protected ListNationsTerreirosAction $listNationsTerreirosAction,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $list = $this->listNationsTerreirosAction->execute();

            return $this->returnResponse(
                true,
                null,
                $list,
                Response::HTTP_OK,
                null,
                $list->count(),
            );
        } catch (Exception|Throwable $e) {
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
