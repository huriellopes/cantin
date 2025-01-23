<?php

namespace App\Services\Users;

use App\Models\User;

class DelUsersService
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
