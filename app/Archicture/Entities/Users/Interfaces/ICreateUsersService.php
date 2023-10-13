<?php

namespace App\Archicture\Entities\Users\Interfaces;

use App\Archicture\Entities\Users\Models\User;

interface ICreateUsersService
{
    /**
     * @param object $params
     * @return User
     */
    public function create(object $params) : User;
}
