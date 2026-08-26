<?php

namespace App\Services;

use App\DTOs\CategoryDTO;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function getAll(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function findOrFail(int $id): Category
    {
        return Category::findOrFail($id);
    }

    public function create(CategoryDTO $categoryDTO): Category
    {
        return Category::create([
            'name' => $categoryDTO->name,
            'description' => $categoryDTO->description,
        ]);
    }

    public function update(Category $category, CategoryDTO $dto): Category
    {
        $category->update([
            'name' => $dto->name,
            'description' => $dto->description,
        ]);

        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
