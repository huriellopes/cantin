<?php

namespace App\Models;

/**
 * Class ParternEntity
 * App\Models\ParternEntity
 * @property $name
 * @property $activity_carried_out
 * @property $email
 * @property $phone
 * @property $address_id
 */
class ParternEntity extends GenericModels
{
    protected $table = 'parterns_entities';

    protected $fillable = [
        'name',
        'activity_carried_out',
        'email',
        'phone',
        'address_id'
    ];
}
