<?php

namespace App\Http\Requests\Supplier;

use App\DTOs\SupplierDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|string|max:150',
            'phone'          => 'nullable|string|max:20',
            'email'          => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('suppliers', 'email')->ignore($this->route('supplier')),
            ],
            'contact_person' => 'nullable|string|max:100',
        ];
    }

    public function toDTO(): SupplierDTO
    {
        return SupplierDTO::fromRequest($this->validated());
    }
}
