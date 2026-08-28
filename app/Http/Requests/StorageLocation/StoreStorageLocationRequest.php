<?php

namespace App\Http\Requests\StorageLocation;

use App\DTOs\StorageLocationDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreStorageLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function toDTO(): StorageLocationDTO
    {
        return StorageLocationDTO::fromRequest($this->validated());
    }
}
