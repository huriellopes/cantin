<?php

namespace App\Archicture\Entities\Users\Actions;

use App\Archicture\Entities\Users\Interfaces\IDelUsersService;
use App\Archicture\Entities\Users\Models\User;

class DelUsersAction
{
    /**
     * @param IDelUsersService $IdelUsersService
     */
    public function __construct(
        protected IDelUsersService $IdelUsersService,
    ){}

    /**
     * @param User $user
     * @return bool
     */
    public function execute(User $user) : bool
    {
        return $this->IdelUsersService->delete($user);
    }
}
