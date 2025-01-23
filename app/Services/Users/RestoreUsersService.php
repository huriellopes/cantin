<?php

namespace App\Services\Users;

use App\Models\User;

class RestoreUsersService
{
    /**
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $user = User::onlyTrashed()->where('id', $id)->first();

        return $user->restore();
    }
}
