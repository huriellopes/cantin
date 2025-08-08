<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\Status;

class ExternalLink extends Model
{
    protected $fillable = [
        'name',
        'url',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }
}
