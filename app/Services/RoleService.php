<?php

namespace App\Services;

use App\DTOs\RoleDTO;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function getAll(): Collection
    {
        return Role::all();
        // return Role::with('permissions')->get();
    }

    public function findOrFail(int $id): Role
    {
        return Role::findOrFail($id);
        // return Role::with('permissions')->findOrFail($id);
    }

    public function create(RoleDTO $dto): Role
    {
        return Role::create([
            'name'        => $dto->name,
            'description' => $dto->description,
        ]);
    }

    public function update(Role $role, RoleDTO $dto): Role
    {
        $role->update([
            'name'        => $dto->name,
            'description' => $dto->description,
        ]);

        return $role->fresh();
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }
}
