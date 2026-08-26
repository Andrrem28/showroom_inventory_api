<?php

namespace App\Http\Requests\Category;

use App\DTOs\CategoryDTO;
use Illuminate\Foundation\Http\FormRequest;


class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function toDTO(): CategoryDTO
    {
        return CategoryDTO::fromRequest($this->validated());
    }
}
