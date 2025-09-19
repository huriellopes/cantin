<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Address extends Model
{
    /* @use HasFactory<\Database\Factories\AddressFactory> */
    use KeepsDeletedModels, HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'zipcode',
        'address',
        'complement',
        'number',
        'neighborhood',
        'state_id',
        'city_id',
        'latitude',
        'longitude',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

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
