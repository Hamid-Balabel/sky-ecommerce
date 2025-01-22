<?php

namespace App\Http\Resources\User;

use App\Rules\CheckSamePassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'sometimes|nullable|regex:/^[0-9]+$/|digits:10|unique:users,phone,' . auth()->id(),
            'avatar' => 'nullable|file|mimes:png,jpg,jpeg,svg,webp',
            'password' => ['sometimes', 'nullable', 'min:8', 'confirmed', new CheckSamePassword],
        ];
    }
}
