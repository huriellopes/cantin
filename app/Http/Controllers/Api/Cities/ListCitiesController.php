<?php

namespace App\Http\Controllers\Api\Cities;

use App\Archicture\Entities\Cities\Actions\ListCitiesAction;
use App\Archicture\Generics\TraitsGenerals\MessagesDefaults;
use App\Http\Controllers\Controller;
use App\Http\DTO\Cities\ListCitiesDTO;
use App\Http\Requests\Cities\ListCitiesRequest;
use App\Http\Resources\Cities\ListCitiesResourceCollection;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListCitiesController extends Controller
{
    use Utils;

    /**
     * @param ListCitiesAction $listCitiesAction
     */
    public function __construct(
        protected ListCitiesAction $listCitiesAction
    ){}

    /**
     * @param ListCitiesRequest $request
     * @return JsonResponse
     */
    public function __invoke(ListCitiesRequest $request) : JsonResponse
    {
        try {
            $params = ListCitiesDTO::from($request);

            $cities = (new ListCitiesResourceCollection($this->listCitiesAction->execute($params)));

            return $this->returnResponse(
                true,
                null,
                $cities,
                Response::HTTP_OK,
                null,
                $cities->count()
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
