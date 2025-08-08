<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedModel extends Model
{
    protected $fillable = [
        'key',
        'values',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'values' => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
