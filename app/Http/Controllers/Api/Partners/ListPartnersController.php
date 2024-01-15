<?php

namespace App\Http\Controllers\Api\Partners;

use App\Archicture\Entities\Partners\Actions\ListSitePartnersAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Partners\PartnersResource;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ListPartnersController extends Controller
{
    use Utils;

    /**
     * @param ListSitePartnersAction $listSitePartnersAction
     */
    public function __construct(
        protected ListSitePartnersAction $listSitePartnersAction,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $list = PartnersResource::collection($this->listSitePartnersAction->execute());

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
        } catch (Exception $e) {
            dd($e->getMessage(), $e->getTrace());
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
