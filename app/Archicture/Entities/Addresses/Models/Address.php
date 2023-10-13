<?php

namespace App\Archicture\Entities\Addresses\Models;

use App\Archicture\Entities\Cities\Models\City;
use App\Archicture\Entities\States\Models\State;
use App\Archicture\Generics\Models\GenericModels;
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
