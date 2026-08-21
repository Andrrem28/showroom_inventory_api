<?php

namespace App\Services;

use App\DTOs\PermissionDTO;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function getAll(): Collection
    {
        return Permission::with('roles')->get();
    }

    public function findOrFail(int $id): Permission
    {
        return Permission::with('roles')->findOrFail($id);
    }

    public function create(PermissionDTO $permissionDTO): Permission
    {
        return Permission::create([
            'name'        => $permissionDTO->name,
            'description' => $permissionDTO->description,
        ]);
    }

    public function update(Permission $permission, PermissionDTO $permissionDTO): Permission
    {
        $permission->update([
            'name'        => $permissionDTO->name,
            'description' => $permissionDTO->description,
        ]);

        return $permission->fresh();
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
