<?php

namespace App\Archicture\Entities\Terreiros\Models;

use App\Archicture\Entities\Addresses\Models\Address;
use App\Archicture\Entities\NationsTerreiros\Models\NationsTerreiro;
use App\Archicture\Entities\TerreirosQuestions\Models\TerreiroQuestion;
use App\Archicture\Generics\Models\GenericModels;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Terreiro
 * @package App\Archicture\Entities\Terreiros\Models
 * @property string $name
 * @property string $phone
 * @property string $fundationed_at
 * @property int $nation_terreiro_id
 * @property string $leadership_orunko
 * @property string $color_of_leadership
 * @property $address_id
 */
class Terreiro extends GenericModels
{
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
