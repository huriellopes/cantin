<?php

namespace App\Services\Logs;

use App\Models\Logs;

class CreateLogService
{
    /**
     * @param object $params
     * @return Logs
     */
    public function create(object $params): Logs
    {
        $log = new Logs();
        $log->action = $params->action;
        $log->ip = request()->ip();
        $log->type = $params->type;
        $log->content = json_encode($params->content);
        $log->user_id = auth()->check() ? auth()->user()->id : null;

        $log->save();

        return $log;
    }
}
