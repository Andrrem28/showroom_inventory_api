<?php

namespace App\Http\Requests\Client;

use App\DTOs\ClientDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'sometimes|string|max:150',
            'phone' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('clients', 'email')->ignore($this->route('client')),
            ],
        ];
    }

    public function toDTO(): ClientDTO
    {
        return ClientDTO::fromRequest($this->validated());
    }
}
