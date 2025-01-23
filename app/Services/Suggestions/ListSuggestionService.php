<?php

namespace App\Services\Suggestions;

use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Collection;

class ListSuggestionService
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
