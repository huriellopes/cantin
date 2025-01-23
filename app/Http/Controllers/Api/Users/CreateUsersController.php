<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\DTO\Users\CreateUsersDTO;
use App\Http\Requests\Users\CreateUsersRequest;
use App\Services\Users\CreateUsersService;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreateUsersController extends Controller
{
    use Utils;

    /**
     * @param CreateUsersService $createUsersService
     */
    public function __construct(
        protected CreateUsersService $createUsersService,
    ){}

    /**
     * @param CreateUsersRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function __invoke(CreateUsersRequest $request) : JsonResponse
    {
        try {
            $params = CreateUsersDTO::from($request);

            $this->createUsersService->create($params);
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
