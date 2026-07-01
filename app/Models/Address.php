<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * CEP sem máscara no banco (só dígitos), exibido como 99999-999.
     */
    protected function zipcode(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value): ?string {
                $digits = preg_replace('/\D/', '', (string) $value);

                return mb_strlen((string) $digits) === 8
                    ? mb_substr($digits, 0, 5) . '-' . mb_substr($digits, 5, 3)
                    : ($value === null ? null : (string) $value);
            },
            set: fn (mixed $value): ?string => ($d = preg_replace('/\D/', '', (string) $value)) === '' ? null : $d,
        );
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
