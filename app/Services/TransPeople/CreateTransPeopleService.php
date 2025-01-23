<?php

namespace App\Services\TransPeople;

use App\Models\TransPeople;

class CreateTransPeopleService
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
