<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\ListUsersResourceCollection;
use App\Services\Users\ListUsersService;
use App\Traits\Utils;
use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListUsersController extends Controller
{
    use Utils;

    /**
     * @param ListUsersService $listUsersService
     */
    public function __construct(
        protected ListUsersService $listUsersService,
    ){}

    /**
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke() : JsonResponse
    {
        $this->authorize('view', User::class);
        try {
            $users = (new ListUsersResourceCollection($this->listUsersService->list()));

            return $this->returnResponse(
                true,
                null,
                $users,
                Response::HTTP_OK,
                null,
                $users->count()
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
