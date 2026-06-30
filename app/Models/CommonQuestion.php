<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Model;
use Override;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * Class CommonQuestion
 *
 * @property $id
 * @property $question
 * @property $answer
 * @property $status
 */
class CommonQuestion extends Model
{
    use KeepsDeletedModels;

    /**
     * @var string[]
     */
    protected $fillable = [
        'question',
        'answer',
        'status',
    ];

    public function scopeActive()
    {
        return $this->where('status', '=', Status::ACTIVE);
    }

    /**
     * @return string[]
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
