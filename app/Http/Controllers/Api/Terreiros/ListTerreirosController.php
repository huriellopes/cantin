<?php

namespace App\Http\Controllers\Api\Terreiros;

use App\Archicture\Entities\Terreiros\Actions\ListTerreirosAction;
use App\Http\Controllers\Controller;
use App\Traits\MessagesDefaults;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListTerreirosController extends Controller
{
    use Utils;

    /**
     * @param ListTerreirosAction $listTerreirosAction
     */
    public function __construct(
        protected ListTerreirosAction $listTerreirosAction,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $terreiros = $this->listTerreirosAction->execute();

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
