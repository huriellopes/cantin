<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Role extends Model
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
