<?php

namespace App\Archicture\Entities\Users\Interfaces;

interface IRestoreUsersService
{
    /**
     * @param int $id
     * @return bool
     */
    public function restore(int $id) : bool;
}
