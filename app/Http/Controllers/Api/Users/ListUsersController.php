<?php

namespace App\Http\Controllers\Api\Users;

use App\Archicture\Entities\Users\Actions\ListUsersAction;
use App\Archicture\Entities\Users\Models\User;
use App\Archicture\Generics\TraitsGenerals\MessagesDefaults;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\ListUsersResourceCollection;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ListUsersController extends Controller
{
    use Utils;

    /**
     * @param ListUsersAction $listUsersAction
     */
    public function __construct(
        protected ListUsersAction $listUsersAction,
    ){}

    /**
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke() : JsonResponse
    {
        $this->authorize('view', User::class);
        try {
            $users = (new ListUsersResourceCollection($this->listUsersAction->execute()));

            return $this->returnResponse(
                true,
                null,
                $users,
                Response::HTTP_OK,
                null,
                $users->count()
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
