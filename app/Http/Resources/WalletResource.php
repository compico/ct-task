<?php

namespace App\Http\Resources;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Wallet $resource
 */
class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $amount = $this->resource->GetBalance()->getAmount() / 100;

        // Тут по хорошему через локали делать
        // Но Intl плохо работает с alpine из-за того что не используется glibc
        $balance = sprintf('%s %s',
            number_format($amount, 2, ',', ' '),
            '₽'
        );

        return [
            'id' => $this->resource->getId(),
            'user_id' => $this->resource->GetUserId(),
            'balance' => $balance,
        ];
    }
}
