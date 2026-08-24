<?php

namespace App\Http\Requests\User;

use App\DTOs\UserDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'sometimes|string|max:100',
            'email'     => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],
            'password'  => 'sometimes|string|min:8|confirmed',
            'is_active' => 'boolean',
        ];
    }

    public function toDTO(): UserDTO
    {
        return UserDTO::fromArray($this->validated());
    }
}
