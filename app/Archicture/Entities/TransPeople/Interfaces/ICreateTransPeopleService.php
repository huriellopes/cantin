<?php

namespace App\Archicture\Entities\TransPeople\Interfaces;

use App\Archicture\Entities\TransPeople\Models\TransPeople;

interface ICreateTransPeopleService
{
    /**
     * @param object $params
     * @return TransPeople
     */
    public function create(object $params) : TransPeople;
}
