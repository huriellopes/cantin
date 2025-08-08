<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'abbr',
        'slug'
    ];

    /**
     * @return string[]
     */
    public function casts() : array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return 'slug';
    }
}
