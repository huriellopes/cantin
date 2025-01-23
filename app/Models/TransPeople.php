<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TransPeople
 * @package App\Archicture\Entities\TransPeople\Models
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $address_id
 */
class TransPeople extends GenericModels
{
    protected $table = 'trans_peoples';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_id',
    ];

    /**
     * @return BelongsTo
     */
    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
