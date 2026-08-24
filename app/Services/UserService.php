<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\Role;
Use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAll(): Collection
    {
        return User::with('roles.permissions')->get();
    }

    public function findOrFail(int $id): User
    {
        return User::with('roles.permissions')->findOrFail($id);
    }

    public function create(UserDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'is_active' => $dto->is_active,
        ]);
    }

    public function update(User $user, UserDTO $dto): User
    {
        $data = [
            'name' => $dto->name,
            'email'=> $dto->email,
            'is_active' => $dto->is_active,
        ];

        // Só atualiza a senha se foi informada
        if ($dto->password) {
            $data['password'] = Hash::make($dto->password);
        }

        $user->update($data);

        return $user->fresh('roles.permissions');
    }

    public function delete(User $user): void
    {
        $user->delete();
    }


    // ─────────────────────────────────────
    // Gerenciamento de roles no usuário
    // ─────────────────────────────────────

    // Substitui todos os roles do usuário
    public function syncRoles(User $user, array $rolesIds): User
    {
        $user->roles()->sync($rolesIds);

        return $user->fresh('roles.permissions');
    }

    // Adiciona roles sem remover os existentes
    public function attachRoles(User $user, array $roleIds): User
    {
        $user->roles()->syncWithoutDetaching($roleIds);

        return $user->fresh('roles.permissions');
    }

    // Remove roles específicos do usuário
    public function detachRoles(User $user, array $roleIds): User
    {
        $user->roles()->detach($roleIds);

        return $user->fresh('roles.permissions');
    }

    // Valida se os IDs existem na tabela roles
    public function resolveRoleIds(array $ids): array
    {
        return Role::whereIn('id', $ids)->pluck('id')->toArray();
    }
}
