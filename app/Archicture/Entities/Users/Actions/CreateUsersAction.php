<?php

namespace App\Archicture\Entities\Users\Actions;

use App\Archicture\Entities\Users\Interfaces\ICreateUsersService;
use App\Archicture\Entities\Users\Models\User;

class CreateUsersAction
{
    public function __construct(
        protected ICreateUsersService $IcreateUsersService
    ){}

    /**
     * @param object $params
     * @return User
     */
    public function execute(object $params) : User
    {
        return $this->IcreateUsersService->create($params);
    }
}
