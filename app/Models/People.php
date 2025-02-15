<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $table = "peoples";

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth',
        'sex',
        'description_sex',
        'address_id',
        'type_people_id',
    ];

    /**
     * @return string[]
     */
    public function casts() : array
    {
        return [
            'birth' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
