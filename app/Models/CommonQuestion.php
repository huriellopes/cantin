<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Status as StatusEnum;

/**
 * Class CommonQuestion
 * App\Models\CommonQuestion
 * @property $question
 * @property $answer
 * @property $status
 */
class CommonQuestion extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'question',
        'answer',
        'status'
    ];

    /**
     * @return string[]
     */
    protected function casts() : array
    {
        return [
            'status' => StatusEnum::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime'
        ];
    }
}
