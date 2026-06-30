<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Address extends Model
{
    /* @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory, KeepsDeletedModels;

    /**
     * @var string[]
     */
    protected $fillable = [
        'zipcode',
        'address',
        'complement',
        'neighborhood',
        'state_id',
        'city_id',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
