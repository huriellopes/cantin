<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class PartnerEntity extends Model
{
    /* @use HasFactory<\Database\Factories\PartnerEntityFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $table = 'partners_entities';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'activity_carried_out',
        'email',
        'phone',
        'address_id',
        'path_image',
        'user_id',
        'status',
    ];

    /**
     * @return string[]
     */
    #[Override]
    public function casts(): array
    {
        return [
            'status' => Status::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
