<?php

namespace App\Archicture\Entities\TransPeople\Actions;

use App\Archicture\Entities\TransPeople\Interfaces\ICreateTransPeopleService;
use App\Archicture\Entities\TransPeople\Models\TransPeople;
use App\Archicture\Entities\TransPeople\Validates\TransPeopleValidate;

class CreateTransPeopleAction
{
    /**
     * @param ICreateTransPeopleService $IcreateTransPeopleService
     */
    public function __construct(
        protected ICreateTransPeopleService $IcreateTransPeopleService,
    ){}

    /**
     * @param object $params
     * @return TransPeople
     * @throws \Throwable
     */
    public function execute(object $params) : TransPeople
    {
        $this->getValidate()->validaParametros($params);
        return $this->IcreateTransPeopleService->create($params);
    }

    /**
     * @return TransPeopleValidate
     */
    private function getValidate() : TransPeopleValidate
    {
        return new TransPeopleValidate();
    }
}
