<?php

namespace App\Http\Resources\Users;

use App\Archicture\Entities\Levels\Enum\LevelEnum;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ListUsersResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'level' => LevelEnum::from($this?->level?->id)->getName(),
            'status' => !empty($this->getVerifyStatusAttribute()) ? 'Inativo' : 'Ativo',
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i')
        ];
    }
}
