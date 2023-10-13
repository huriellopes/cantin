<?php

namespace App\Http\Resources\States;

use Illuminate\Http\Resources\Json\JsonResource;

class ListStatesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'acronym' => $this->acronym,
            'description' => $this->description,
        ];
    }
}
