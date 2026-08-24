<?php

namespace App\Http\Requests\User;

use App\DTOs\UserDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
        ];
    }

    public function toDTO(): UserDTO
    {
        return UserDTO::fromArray($this->validated());
    }
}
