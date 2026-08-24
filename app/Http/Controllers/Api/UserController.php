<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service
    ) {}

    // GET /api/users
    public function index()
    {
        $users = $this->service->getAll();

        return UserResource::collection($users);
    }

    // GET /api/users/{id}
    public function show(int $id)
    {
        $user = $this->service->findOrFail($id);

        return new UserResource($user);
    }

     // POST /api/users
    public function store(StoreUserRequest $request)
    {
        $user = $this->service->create($request->toDTO());

        return new UserResource($user);
    }

    // PUT /api/users/{id}
    public function update(UpdateUserRequest $request, int $id)
    {
        $user    = $this->service->findOrFail($id);
        $updated = $this->service->update($user, $request->toDTO());

        return new UserResource($updated);
    }


    // DELETE /api/users/{id}
    public function destroy(int $id)
    {
        $user = $this->service->findOrFail($id);
        $this->service->delete($user);

        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }

    // ─────────────────────────────────────
    // Gerenciamento de roles no usuário
    // ─────────────────────────────────────

    // PUT /api/users/{id}/roles
    // Substitui TODOS os roles do usuário
    public function syncRoles(Request $request, int $id)
    {
        $request->validate([
            'roles'   => 'required|array',
            'roles.*' => 'integer|exists:App\Models\Role,id',
        ]);

        $user    = $this->service->findOrFail($id);
        $ids     = $this->service->resolveRoleIds($request->roles);
        $updated = $this->service->syncRoles($user, $ids);

        return new UserResource($updated);
    }

    // POST /api/users/{id}/roles
    // Adiciona roles SEM remover os existentes
    public function attachRoles(Request $request, int $id)
    {
        $request->validate([
            'roles'   => 'required|array',
            'roles.*' => 'integer|exists:App\Models\Role,id',
        ]);

        $user    = $this->service->findOrFail($id);
        $ids     = $this->service->resolveRoleIds($request->roles);
        $updated = $this->service->attachRoles($user, $ids);

        return new UserResource($updated);
    }

    // DELETE /api/users/{id}/roles
    // Remove roles específicos do usuário
    public function detachRoles(Request $request, int $id)
    {
        $request->validate([
            'roles'   => 'required|array',
            'roles.*' => 'integer|exists:App\Models\Role,id',
        ]);

        $user    = $this->service->findOrFail($id);
        $ids     = $this->service->resolveRoleIds($request->roles);
        $updated = $this->service->detachRoles($user, $ids);

        return new UserResource($updated);
    }
}
