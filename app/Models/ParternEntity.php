<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParternEntity extends Model
{
    use SoftDeletes;

    protected $table = 'partners_entities';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'activity_carried_out',
        'email',
        'phone',
        'address_id'
    ];

    /**
     * @return string[]
     */
    public function casts() : array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime'
        ];
    }
}
