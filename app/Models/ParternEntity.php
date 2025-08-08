<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class ParternEntity extends Model
{
    use KeepsDeletedModels;

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
        ];
    }
}
