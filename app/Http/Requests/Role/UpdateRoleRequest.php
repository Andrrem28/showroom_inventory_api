<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\RoleDTO;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:50|unique:roles,name,' . $this->route('role'),
            'description' => 'nullable|string|max:255',
        ];
    }

    public function toDTO(): RoleDTO
    {
        return RoleDTO::fromRequest($this->validated());
    }
}
