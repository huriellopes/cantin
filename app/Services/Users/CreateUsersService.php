<?php

namespace App\Services\Users;

use App\Models\User;
use App\Validates\CreateUsersValidate;
use App\Traits\Utils;
use Throwable;

class CreateUsersService
{
    use Utils;

    /**
     * @param object $params
     * @return User
     * @throws Throwable
     */
    public function create(object $params): User
    {
        $this->getValidate()->validaParametros($params);

        $user = new User();
        $user->name = $params->name;
        $user->username = $this->setUserNameUser($params->name);
        $user->email = $params->email;
        $user->level_id = $params->level_id;
        $user->password = bcrypt(123456);

        $user->save();

        return $user;
    }

    /**
     * @return CreateUsersValidate
     */
    private function getValidate() : CreateUsersValidate
    {
        return new CreateUsersValidate();
    }
}
