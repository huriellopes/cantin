<?php

namespace App\Models;

/**
 * Class Logs
 * @package App\Archicture\Entities\Logs\Models
 * @property int $id
 * @property string $action
 * @property string $ip
 * @property string $type
 * @property string $content
 * @property int $user_id
 */
class Logs extends GenericModels
{
    protected $table = 'logs';

    protected $fillable = [
        'action',
        'ip',
        'type',
        'content',
        'user_id',
    ];
}
