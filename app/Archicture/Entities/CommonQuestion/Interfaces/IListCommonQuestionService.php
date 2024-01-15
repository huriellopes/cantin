<?php

namespace App\Archicture\Entities\CommonQuestion\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IListCommonQuestionService
{
    /**
     * @return Collection
     */
    public function list() : Collection;
}
