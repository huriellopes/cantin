<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Terreiro extends Model
{
    use KeepsDeletedModels;

    protected $table = "terreiros";

    protected $fillable = [
        'name',
        'phone',
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
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
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

    public function question()
    {
        return $this->hasOne(TerreiroQuestion::class);
    }
}
