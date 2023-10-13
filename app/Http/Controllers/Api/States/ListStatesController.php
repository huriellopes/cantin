<?php

namespace App\Http\Controllers\Api\States;

use App\Archicture\Entities\States\Actions\ListStatesAction;
use App\Archicture\Generics\TraitsGenerals\MessagesDefaults;
use App\Http\Controllers\Controller;
use App\Http\Resources\States\ListStatesResourceCollection;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListStatesController extends Controller
{
    use Utils;

    /**
     * @param ListStatesAction $listStatesAction
     */
    public function __construct(
        protected ListStatesAction $listStatesAction
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $states = (new ListStatesResourceCollection($this->listStatesAction->execute()));

            return $this->returnResponse(
                true,
                null,
                $states,
                Response::HTTP_OK,
                null,
                $states->count(),
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
