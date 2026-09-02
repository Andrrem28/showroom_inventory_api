<?php

namespace App\Http\Requests\Client;

use App\DTOs\ClientDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:150',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150|unique:clients,email',
        ];
    }

    public function toDTO(): ClientDTO
    {
        return ClientDTO::fromRequest($this->validated());
    }
}
