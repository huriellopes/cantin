<?php

namespace App\Archicture\Entities\Users\Actions;

use App\Archicture\Entities\Users\Interfaces\IListUsersService;
use Illuminate\Database\Eloquent\Collection;

class ListUsersAction
{
    /**
     * @param IListUsersService $IlistUsersService
     */
    public function __construct(
        protected IListUsersService $IlistUsersService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistUsersService->list();
    }
}
