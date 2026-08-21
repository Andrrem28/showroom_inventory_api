<?php

namespace App\Services;

use App\DTOs\RoleDTO;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function getAll(): Collection
    {
        return Role::with('permissions')->get();
    }

    public function findOrFail(int $id): Role
    {
        return Role::with('permissions')->findOrFail($id);
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

        return $role->fresh('permissions');
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }


    // Substitui todas as permissions do role
    public function syncPermissions(Role $role, array $permissionIds): Role
    {
        $role->permissions()->sync($permissionIds);

        return $role->fresh('permissions');
    }

    // Adiciona permissions sem remover as existentes
    public function attachPermissions(Role $role, array $permissionIds): Role
    {
        $role->permissions()->syncWithoutDetaching($permissionIds);

        return $role->fresh('permissions');
    }

    // Remove permissions específicas do role
    public function detachPermissions(Role $role, array $permissionIds): Role
    {
        $role->permissions()->detach($permissionIds);

        return $role->fresh('permissions');
    }

    // Valida se os IDs existem na tabela permissions
    public function resolvePermissionIds(array $ids): array
    {
        return Permission::whereIn('id', $ids)->pluck('id')->toArray();
    }
}
