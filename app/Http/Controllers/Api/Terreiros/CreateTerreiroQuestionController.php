<?php

namespace App\Http\Controllers\Api\Terreiros;

use App\Archicture\Entities\TerreirosQuestions\Actions\CreateTerreiroQuestionAction;
use App\Http\Controllers\Controller;
use App\Http\DTO\Terreiro\QuestionDTO;
use App\Http\Requests\Terreiros\QuestionRequest;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;

class CreateTerreiroQuestionController extends Controller
{
    use Utils;

    /**
     * @param CreateTerreiroQuestionAction $createTerreiroQuestionAction
     */
    public function __construct(
        protected CreateTerreiroQuestionAction $createTerreiroQuestionAction,
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

            $this->createTerreiroQuestionAction->execute($id, $params);

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_CREATED],
                null,
                Response::HTTP_CREATED,
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
