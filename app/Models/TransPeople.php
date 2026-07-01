<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Status;
use App\Models\Concerns\FormatsPhone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class TransPeople extends Model
{
    /* @use HasFactory<\Database\Factories\TransPeopleFactory> */
    use FormatsPhone, HasFactory, KeepsDeletedModels;

    protected $table = 'trans_peoples';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_id',
        'status',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
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
