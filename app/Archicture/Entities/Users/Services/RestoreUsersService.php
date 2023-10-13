<?php

namespace App\Archicture\Entities\Users\Services;

use App\Archicture\Entities\Users\Interfaces\IRestoreUsersService;
use App\Archicture\Entities\Users\Models\User;

class RestoreUsersService implements IRestoreUsersService
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
