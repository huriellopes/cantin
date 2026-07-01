<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Override;
use OwenIt\Auditing\Models\Audit as BaseAudit;

/**
 * Model de auditoria (owen-it) com tipagem explícita para o painel.
 *
 * @property int $id
 * @property string $event
 * @property string $auditable_type
 * @property int $auditable_id
 * @property array<string, mixed>|null $old_values
 * @property array<string, mixed>|null $new_values
 * @property string|null $url
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $tags
 * @property Carbon|null $created_at
 * @property-read User|null $user
 */
class Audit extends BaseAudit
{
    /**
     * Usuário que originou a auditoria (tipado para o resolver do pacote).
     *
     * @return MorphTo<Model, $this>
     */
    #[Override]
    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
