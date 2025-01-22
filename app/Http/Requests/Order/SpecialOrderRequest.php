<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatusEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class SpecialOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['required'],
            'customer_id' => ['required', 'exists:users,id'],
            'handyman_id' => ['nullable', 'exists:users,id'],
            'delivery_time' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:today'],
            'status' => ['required', new Enum(OrderStatusEnum::class)],
            'note' => ['nullable'],
            'location' => ['required'],
        ];
    }

}
