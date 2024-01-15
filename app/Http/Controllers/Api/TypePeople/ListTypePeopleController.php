<?php

namespace App\Http\Controllers\Api\TypePeople;

use App\Archicture\Entities\TypePeoples\Actions\ListTypePeopleAction;
use App\Http\Controllers\Controller;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;

class ListTypePeopleController extends Controller
{
    use Utils;

    /**
     * @param ListTypePeopleAction $listTypePeopleAction
     */
    public function __construct(
        protected ListTypePeopleAction $listTypePeopleAction,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $listTypePeople = $this->listTypePeopleAction->execute();

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_OK],
                $listTypePeople,
                Response::HTTP_OK,
                null,
                $listTypePeople->count(),
            );
        } catch (Exception|Throwable $e) {
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
