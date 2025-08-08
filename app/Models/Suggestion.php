<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;


class Suggestion extends Model
{
    use KeepsDeletedModels;

    protected $table = "suggestions";

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description'
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
