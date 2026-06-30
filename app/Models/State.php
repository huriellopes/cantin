<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class State extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'abbr',
        'slug',
        'ibge_code',
    ];

    /**
     * @return string[]
     */
    #[Override]
    public function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
