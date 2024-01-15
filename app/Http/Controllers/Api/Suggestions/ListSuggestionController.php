<?php

namespace App\Http\Controllers\Api\Suggestions;

use App\Archicture\Entities\Suggestions\Actions\ListSuggestionAction;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Throwable;
use Exception;
use App\Traits\Utils;

class ListSuggestionController extends Controller
{
    use Utils;

    /**
     * @param ListSuggestionAction $listSuggestionAction
     */
    public function __construct(
        protected ListSuggestionAction $listSuggestionAction,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $listSuggestion = $this->listSuggestionAction->execute();

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_OK],
                $listSuggestion,
                Response::HTTP_OK,
                null,
                $listSuggestion->count(),
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
