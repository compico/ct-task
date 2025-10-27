<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read int $user_id
 * @property-read float $amount
 * @property-read string $comment
 */
class TransactionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|gt:0',
            'comment' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
