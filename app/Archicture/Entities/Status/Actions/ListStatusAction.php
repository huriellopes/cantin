<?php

namespace App\Archicture\Entities\Status\Actions;

use App\Archicture\Entities\Status\Interfaces\IListStatusService;
use Illuminate\Database\Eloquent\Collection;

class ListStatusAction
{
    /**
     * @param IListStatusService $IlistStatusService
     */
    public function __construct(
        protected IListStatusService $IlistStatusService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistStatusService->list();
    }
}
