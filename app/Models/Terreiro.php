<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terreiro extends Model
{
    use SoftDeletes;

    protected $table = "terreiros";

    protected $fillable = [
        'name',
        'phone',
        'fundationed_at',
        'nation_terreiro_id',
        'leadership_orunko',
        'color_of_leadership',
        'address_id',
    ];

    /**
     * @return string[]
     */
    public function casts() : array
    {
        return [
            'fundationed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime'
        ];
    }

    /**
     * @return BelongsTo
     */
    public function nation() : BelongsTo
    {
        return $this->belongsTo(NationsTerreiro::class, 'nation_terreiro_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return BelongsTo
     */
    public function question() : BelongsTo
    {
        return $this->belongsTo(TerreiroQuestion::class);
    }
}
