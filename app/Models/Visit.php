<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'page',
        'referer',
        'visit_time',
    ];

    /**
     * @return string[]
     */
    protected function casts() : array
    {
        return [
            'visited_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }
}
