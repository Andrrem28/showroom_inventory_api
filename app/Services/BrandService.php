<?php

namespace App\Services;

use App\DTOs\BrandDTO;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandService
{
    public function getAll(): Collection
    {
        return Brand::orderBy('name')->get();
    }

    public function findOrFail(int $id): Brand
    {
        return Brand::findOrFail($id);
    }

    public function create(BrandDTO $dto): Brand
    {
        return Brand::create([
            'name'=> $dto->name,
        ]);
    }

    public function update(Brand $brand, BrandDTO $dto): Brand
    {
        $brand->update([
            'name' => $dto->name,
        ]);

        return $brand->fresh();
    }

    public function delete(Brand $brand): void
    {
        $brand->delete();
    }
}
