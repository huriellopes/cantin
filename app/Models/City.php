<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'state_id'
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

    public function state() : BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
