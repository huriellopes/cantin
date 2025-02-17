<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TypeTerreiro
 * @package App\Models
 * @property string $name
 * @property string $description
 */
class TypeTerreiro extends Model
{
    use SoftDeletes;

    protected $table = "type_terreiros";

    /**
     * @var string[]
     */
    protected $fillable = [
        "name",
        "description"
    ];

    /**
     * @return string[]
     */
    protected function casts() : array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime'
        ];
    }
}
