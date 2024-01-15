<?php

namespace App\Archicture\Entities\TransPeople\Services;

use App\Archicture\Entities\TransPeople\Interfaces\ICreateTransPeopleService;
use App\Archicture\Entities\TransPeople\Models\TransPeople;

class CreateTransPeopleService implements ICreateTransPeopleService
{

    /**
     * @param object $params
     * @return TransPeople
     */
    public function create(object $params): TransPeople
    {
        $transPeople = new TransPeople();
        $transPeople->name = $params->name;
        $transPeople->email = $params->email;
        $transPeople->phone = $params->phone;
        $transPeople->address_id = $params->address_id;

        $transPeople->save();

        return $transPeople;
    }
}
