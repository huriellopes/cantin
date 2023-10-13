<?php

namespace App\Http\Controllers\Api\Levels;

use App\Archicture\Entities\Levels\Actions\ListLevelAction;
use App\Archicture\Generics\TraitsGenerals\MessagesDefaults;
use App\Http\Controllers\Controller;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListLevelController extends Controller
{
    use Utils;

    /**
     * @param ListLevelAction $listLevelAction
     */
    public function __construct(
        protected ListLevelAction $listLevelAction,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $levels = $this->listLevelAction->execute();

            return $this->returnResponse(
                true,
                null,
                $levels,
                Response::HTTP_OK,
                null,
                $levels->count()
            );
        } catch(Exception $exception) {
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
