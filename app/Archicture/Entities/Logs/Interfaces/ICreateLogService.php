<?php

namespace App\Archicture\Entities\Logs\Interfaces;

use App\Archicture\Entities\Logs\Models\Logs;

interface ICreateLogService
{
    /**
     * @param object $params
     * @return Logs
     */
    public function create(object $params) : Logs;
}
