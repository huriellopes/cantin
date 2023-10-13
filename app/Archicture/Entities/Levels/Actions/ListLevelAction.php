<?php

namespace App\Archicture\Entities\Levels\Actions;

use App\Archicture\Entities\Levels\Interfaces\IListLevelService;
use Illuminate\Database\Eloquent\Collection;

class ListLevelAction
{
    /**
     * @param IListLevelService $IlistLevelService
     */
    public function __construct(
        protected IListLevelService $IlistLevelService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistLevelService->listLevels();
    }
}
