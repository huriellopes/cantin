<?php

namespace App\Http\Controllers\Api\Users;

use App\Archicture\Entities\Users\Actions\CreateUsersAction;
use App\Http\Controllers\Controller;
use App\Http\DTO\Users\CreateUsersDTO;
use App\Http\Requests\Users\CreateUsersRequest;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Exception;

class CreateUsersController extends Controller
{
    use Utils;

    /**
     * @param CreateUsersAction $createUsersAction
     */
    public function __construct(
        protected CreateUsersAction $createUsersAction,
    ){}

    public function __invoke(CreateUsersRequest $request) : JsonResponse
    {
        try {
            $params = CreateUsersDTO::from($request);

            $this->createUsersAction->execute($params);
        } catch (Exception $exception) {}
    }
}
