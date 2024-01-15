<?php

namespace App\Archicture\Entities\Suggestions\Interface;

use Illuminate\Database\Eloquent\Collection;

interface IListSuggestionService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
