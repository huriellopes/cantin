<?php

namespace App\Archicture\Entities\Users\Services;

use App\Archicture\Entities\Users\Interfaces\IDelUsersService;
use App\Archicture\Entities\Users\Models\User;

class DelUsersService implements IDelUsersService
{
    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
       return $user->delete();
    }
}
