<?php

namespace App\Archicture\Entities\CommonQuestion\Actions;

use App\Archicture\Entities\CommonQuestion\Interfaces\IListCommonQuestionService;
use Illuminate\Database\Eloquent\Collection;

class ListCommonQuestionAction
{
    /**
     * @param IListCommonQuestionService $IlistCommonQuestionService
     */
    public function __construct(
        protected IListCommonQuestionService $IlistCommonQuestionService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistCommonQuestionService->list();
    }
}
