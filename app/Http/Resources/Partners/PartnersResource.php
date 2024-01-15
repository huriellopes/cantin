<?php

namespace App\Http\Resources\Partners;

use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnersResource extends JsonResource
{
    use Utils;

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->maskPhone($this->phone),
            'path_image' => $this->path_image,
            'user' => $this->user->name,
            'status' => $this->status->name,
        ];
    }
}
