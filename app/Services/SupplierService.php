<?php

namespace App\Services;

use App\DTOs\SupplierDTO;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;

class SupplierService
{
    public function getAll(): Collection
    {
        return Supplier::orderBy('name')->get();
    }

    public function findOrFail(int $id): Supplier
    {
        return Supplier::findOrFail($id);
    }

    public function create(SupplierDTO $dto): Supplier
    {
        return Supplier::create([
            'name'           => $dto->name,
            'phone'          => $dto->phone,
            'email'          => $dto->email,
            'contact_person' => $dto->contact_person,
        ]);
    }

    public function update(Supplier $supplier, SupplierDTO $dto): Supplier
    {
        $supplier->update([
            'name'           => $dto->name,
            'phone'          => $dto->phone,
            'email'          => $dto->email,
            'contact_person' => $dto->contact_person,
        ]);

        return $supplier->fresh();
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }
}
