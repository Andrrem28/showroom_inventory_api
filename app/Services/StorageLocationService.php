<?php

namespace App\Services;

use App\DTOs\StorageLocationDTO;
use App\Models\StorageLocation;
use Illuminate\Database\Eloquent\Collection;

class StorageLocationService
{
    public function getAll(): Collection
    {
        return StorageLocation::orderBy('name')->get();
    }

    public function findOrFail(int $id): StorageLocation
    {
        return StorageLocation::findOrFail($id);
    }

    public function create(StorageLocationDTO $dto): StorageLocation
    {
        return StorageLocation::create([
            'name'        => $dto->name,
            'description' => $dto->description,
        ]);
    }

    public function update(StorageLocation $location, StorageLocationDTO $dto): StorageLocation
    {
        $location->update([
            'name'        => $dto->name,
            'description' => $dto->description,
        ]);

        return $location->fresh();
    }

    public function delete(StorageLocation $location): void
    {
        $location->delete();
    }
}
