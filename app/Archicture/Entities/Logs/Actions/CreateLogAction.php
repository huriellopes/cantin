<?php

namespace App\Archicture\Entities\Logs\Actions;

use App\Archicture\Entities\Logs\Interfaces\ICreateLogService;
use App\Archicture\Entities\Logs\Models\Logs;

class CreateLogAction
{
    /**
     * @param ICreateLogService $IcreateLogService
     */
    public function __construct(
        protected ICreateLogService $IcreateLogService,
    ){}

    /**
     * @param object $params
     * @return Logs
     */
    public function execute(object $params) : Logs
    {
        return $this->IcreateLogService->create($params);
    }
}
