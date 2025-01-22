<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatusEnum;
use App\Enums\UserTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class OrderStatusRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(OrderStatusEnum::class)],
        ];
    }
}
