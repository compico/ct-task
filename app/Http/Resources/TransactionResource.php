<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use NumberFormatter;

/**
 * @property-read Transaction $resource
 */
class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $amount = $this->resource->GetSum()->getAmount() / 100;

        // Тут по хорошему через локали делать
        // Но Intl плохо работает с alpine из-за того что не используется glibc
        $balance = sprintf('%s %s',
            number_format($amount, 2, ',', ' '),
            '₽'
        );

        return [
            'id' => $this->resource->getId(),
            'user_id' => $this->resource->getUserId(),
            'sum' =>  $balance,
            'comment' => $this->resource->getComment(),
            'type' => $this->resource->getType(),
            'parent_transaction' => new TransactionResource($this->whenLoaded('parent_transaction')),
            'wallet' => new WalletResource($this->whenLoaded('wallet')),
        ];
    }
}
