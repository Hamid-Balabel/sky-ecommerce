<?php

namespace App\Http\Requests\User;

use App\Rules\CheckSamePassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'sometimes|nullable|email|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|file|mimes:png,jpg,jpeg,svg,webp',
            'password' => ['sometimes', 'nullable', 'min:8', 'confirmed', new CheckSamePassword],
        ];
    }
}
