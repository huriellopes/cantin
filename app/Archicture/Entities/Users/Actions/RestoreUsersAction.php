<?php

namespace App\Archicture\Entities\Users\Actions;

use App\Archicture\Entities\Users\Interfaces\IRestoreUsersService;

class RestoreUsersAction
{
    /**
     * @param IRestoreUsersService $IrestoreUsersService
     */
    public function __construct(
        protected IRestoreUsersService $IrestoreUsersService,
    ){}

    /**
     * @param int $id
     * @return bool
     */
    public function execute(int $id) : bool
    {
        return $this->IrestoreUsersService->restore($id);
    }
}
