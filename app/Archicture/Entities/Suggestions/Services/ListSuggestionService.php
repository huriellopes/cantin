<?php

namespace App\Archicture\Entities\Suggestions\Services;

use App\Archicture\Entities\Suggestions\Interface\IListSuggestionService;
use App\Archicture\Entities\Suggestions\Models\Suggestion;
use Illuminate\Database\Eloquent\Collection;

class ListSuggestionService implements IListSuggestionService
{

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return Suggestion::query()
            ->select('type_suggestion','description')
            ->get();
    }
}
