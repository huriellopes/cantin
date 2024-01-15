<?php

namespace App\Archicture\Entities\Logs\Actions;

use App\Archicture\Entities\Logs\Interfaces\IListLogService;
use Illuminate\Database\Eloquent\Collection;

class ListLogAction
{
    /**
     * @param IListLogService $IlistLogService
     */
    public function __construct(
        protected IListLogService $IlistLogService
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistLogService->list();
    }
}
