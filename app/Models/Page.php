<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property string $name
 * @property string $slug
 * @property string $content
 * @property Status $status
 */
class Page extends Model
{
    /* @use <\Database\Factories\PageFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'status',
    ];

    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }
}
