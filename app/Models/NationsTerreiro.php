<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class NationsTerreiro extends Model
{
    use KeepsDeletedModels;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return 'slug';
    }

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
}
