<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read int $from_user_id
 * @property-read int $to_user_id
 * @property-read float $amount
 * @property-read string $comment
 */
class TransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from_user_id' => 'required|integer',
            'to_user_id' => 'required|integer',
            'amount' => 'required|numeric|gt:0',
            'comment' => 'string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
