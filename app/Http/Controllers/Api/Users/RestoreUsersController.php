<?php

namespace App\Http\Controllers\Api\Users;

use App\Archicture\Entities\Users\Actions\RestoreUsersAction;
use App\Archicture\Entities\Users\Models\User;
use App\Archicture\Generics\TraitsGenerals\MessagesDefaults;
use App\Http\Controllers\Controller;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RestoreUsersController extends Controller
{
    use Utils;

    /**
     * @param RestoreUsersAction $restoreUsersAction
     */
    public function __construct(
        protected RestoreUsersAction $restoreUsersAction,
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
            $this->restoreUsersAction->execute($id);

            return $this->returnResponse(
                true,
                'Usuário recuperado com sucesso',
                null,
                Response::HTTP_OK
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
