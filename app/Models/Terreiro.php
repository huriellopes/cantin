<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\FormatsPhone;
use Database\Factories\TerreiroFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property string|null $phone
 */
class Terreiro extends Model implements AuditableContract
{
    use Auditable;

    /** @use HasFactory<TerreiroFactory> */
    use FormatsPhone, HasFactory, KeepsDeletedModels;

    protected $table = 'terreiros';

    protected $fillable = [
        'name',
        'phone',
        'nation_terreiro_id',
        'leadership_orunko',
        'color_of_leadership',
        'address_id',
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

    public function nation(): BelongsTo
    {
        return $this->belongsTo(NationsTerreiro::class, 'nation_terreiro_id', 'id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function question()
    {
        return $this->hasOne(TerreiroQuestion::class);
    }
}
