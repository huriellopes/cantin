<?php

namespace App\Archicture\Entities\Suggestions\Actions;

use App\Archicture\Entities\Suggestions\Interface\IListSuggestionService;
use Illuminate\Database\Eloquent\Collection;

class ListSuggestionAction
{
    /**
     * @param IListSuggestionService $IlistSuggestionService
     */
    public function __construct(
        protected IListSuggestionService $IlistSuggestionService,
    ){}

    /**
     * @return Collection
     */
    public function execute() : Collection
    {
        return $this->IlistSuggestionService->list();
    }
}
