<?php

namespace App\Http\Controllers\Api\Terreiros;

use App\Http\Controllers\Controller;
use App\Http\DTO\Terreiro\QuestionDTO;
use App\Http\Requests\Terreiros\QuestionRequest;
use App\Services\TerreirosQuestions\CreateTerreiroQuestionService;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;

class CreateTerreiroQuestionController extends Controller
{
    use Utils;

    /**
     * @param CreateTerreiroQuestionService $createTerreiroQuestionService
     */
    public function __construct(
        protected CreateTerreiroQuestionService $createTerreiroQuestionService,
    ){}

    /**
     * @param int $id
     * @param QuestionRequest $request
     * @return JsonResponse
     */
    public function __invoke(int $id, QuestionRequest $request) : JsonResponse
    {
        try {
            $params = QuestionDTO::from($request);

            $params->terreiro_id = $id;

            $this->createTerreiroQuestionService->create($params);

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_CREATED],
                null,
                Response::HTTP_CREATED,
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
                $e
            );
        }
    }
}
