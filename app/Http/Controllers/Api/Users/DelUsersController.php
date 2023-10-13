<?php

namespace App\Http\Controllers\Api\Users;

use App\Archicture\Entities\Users\Actions\DelUsersAction;
use App\Archicture\Entities\Users\Models\User;
use App\Archicture\Generics\TraitsGenerals\MessagesDefaults;
use App\Http\Controllers\Controller;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DelUsersController extends Controller
{
    use Utils;

    /**
     * @param DelUsersAction $delUsersAction
     */
    public function __construct(
        protected DelUsersAction $delUsersAction
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
            $this->delUsersAction->execute($user);

            return $this->returnResponse(
                true,
                'Usuário deletado com sucesso',
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
