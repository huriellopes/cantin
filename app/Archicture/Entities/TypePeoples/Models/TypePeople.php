<?php

namespace App\Archicture\Entities\TypePeoples\Models;

use App\Archicture\Entities\Terreiros\Models\Terreiro;
use App\Archicture\Generics\Models\GenericModels;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TypePeople
 * @package App\Archicture\Entities\TypePeoples\Models
 * @property int $id
 * @property string $type
 * @property string $description
 */
class TypePeople extends GenericModels
{
    protected $table = 'type_peoples';

    protected $fillable = [
        'type',
        'description',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return HasOne
     */
    public function terreiro() : HasOne
    {
        return $this->hasOne(Terreiro::class, 'type_people_id', 'id');
    }
}
