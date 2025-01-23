<?php

namespace App\Http\Controllers\Api\Suggestions;

use App\Http\Controllers\Controller;
use App\Services\Suggestions\ListSuggestionService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Throwable;
use Exception;
use App\Traits\Utils;

class ListSuggestionController extends Controller
{
    use Utils;

    /**
     * @param ListSuggestionService $listSuggestionService
     */
    public function __construct(
        protected ListSuggestionService $listSuggestionService,
    ){}

    /**
     * @return JsonResponse
     */
    public function __invoke() : JsonResponse
    {
        try {
            $listSuggestion = $this->listSuggestionService->list();

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_OK],
                $listSuggestion,
                Response::HTTP_OK,
                null,
                $listSuggestion->count(),
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
                $e,
            );
        }
    }
}
