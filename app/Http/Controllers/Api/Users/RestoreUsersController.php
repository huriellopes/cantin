<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\Users\RestoreUsersService;
use App\Traits\Utils;
use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RestoreUsersController extends Controller
{
    use Utils;

    /**
     * @param RestoreUsersService $restoreUsersService
     */
    public function __construct(
        protected RestoreUsersService $restoreUsersService,
    ){}

    /**
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(int $id) : JsonResponse
    {
        $this->authorize('view', User::class);
        try {
            $this->restoreUsersService->restore($id);

            return $this->returnResponse(
                true,
                'Usuário recuperado com sucesso',
                null,
                Response::HTTP_OK
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
