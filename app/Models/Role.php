<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Role extends Model
{
    use KeepsDeletedModels;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
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
}
