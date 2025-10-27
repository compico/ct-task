<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read User $resource
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->getName(),
            'email' => $this->resource->getEmail(),
            'wallet' => $this->whenLoaded('wallet', new WalletResource($this->resource->wallet)),
        ];
    }
}
