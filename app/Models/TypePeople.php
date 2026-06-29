<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class TypePeople extends Model
{
    use KeepsDeletedModels;

    protected $table = 'type_peoples';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return string[]
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function terreiro(): HasOne
    {
        return $this->hasOne(Terreiro::class, 'type_people_id', 'id');
    }
}
