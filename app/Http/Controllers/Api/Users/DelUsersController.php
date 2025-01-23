<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\Users\DelUsersService;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Utils;
use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;

class DelUsersController extends Controller
{
    use Utils;

    /**
     * @param DelUsersService $delUsersService
     */
    public function __construct(
        protected DelUsersService $delUsersService
    ){}

    /**
     * @param User $user
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(User $user) : JsonResponse
    {
        $this->authorize('view', User::class);
        try {
            $this->delUsersService->delete($user);

            return $this->returnResponse(
                true,
                'Usuário deletado com sucesso',
                null,
                Response::HTTP_OK
            );
        } catch (Exception $e) {
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
