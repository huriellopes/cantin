<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $description
 * @property $state_id
 */
class City extends GenericModels
{
    protected $table = "cities";

    protected $fillable = [
        'city_name',
        'state_id'
    ];

    public function state() : BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
