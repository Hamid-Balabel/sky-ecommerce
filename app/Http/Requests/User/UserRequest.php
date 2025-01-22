<?php

namespace App\Http\Requests\User;

use App\Enums\UserTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($this->route('user'))
                    ->whereNull('deleted_at')
            ],
            'password' => ['sometimes', 'required', 'min:8', 'confirmed'],
            'type' => ['required', new Enum(UserTypeEnum::class)],
            'avatar' => 'sometimes|nullable|' . vImage(),
        ];
    }
}
