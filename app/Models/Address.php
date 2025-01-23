<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $zipcode
 * @property $address
 * @property $complement
 * @property $number
 * @property $neighborhood
 * @property $state_id
 * @property $city_id
 */
class Address extends GenericModels
{
    protected $table = "addresses";

    protected $fillable = [
        'zipcode',
        'address',
        'complement',
        'number',
        'neighborhood',
        'state_id',
        'city_id',
    ];

    /**
     * @return BelongsTo
     */
    public function state() : BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return BelongsTo
     */
    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
