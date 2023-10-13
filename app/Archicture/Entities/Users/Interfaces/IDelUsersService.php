<?php

namespace App\Archicture\Entities\Users\Interfaces;

use App\Archicture\Entities\Users\Models\User;

interface IDelUsersService
{
    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user) : bool;
}
