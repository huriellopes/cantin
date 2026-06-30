<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class DeletedModel extends Model
{
    protected $fillable = [
        'key',
        'values',
    ];

    /**
     * @return string[]
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'values' => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
