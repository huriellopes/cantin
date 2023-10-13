<?php

namespace App\Archicture\Entities\Addresses\Actions;

use App\Archicture\Entities\Addresses\Interface\ICreateAddressService;
use App\Archicture\Entities\Addresses\Models\Address;

class CreateAddressAction
{
    /**
     * @param ICreateAddressService $IcreateAddressService
     */
    public function __construct(
        protected ICreateAddressService $IcreateAddressService,
    ){}

    /**
     * @param object $params
     * @return Address
     */
    public function execute(object $params) : Address
    {
        return $this->IcreateAddressService->create($params);
    }
}
