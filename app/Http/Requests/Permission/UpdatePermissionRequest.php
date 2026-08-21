<?php

namespace App\Http\Requests\Permission;

use App\DTOs\PermissionDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:permissions,name,' . $this->route('permission'),
            'description' => 'nullable|string|max:255',
        ];
    }

    public function toDTO(): PermissionDTO
    {
        return PermissionDTO::fromRequest($this->validated());
    }
}
