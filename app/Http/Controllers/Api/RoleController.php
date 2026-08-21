<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $service
    ) {}

    // GET /api/roles
    public function index()
    {
        $roles = $this->service->getAll();

        return RoleResource::collection($roles);
    }

    // GET /api/roles/{id}
    public function show(int $id)
    {
        $role = $this->service->findOrFail($id);

        return new RoleResource($role);
    }

    // POST /api/roles
    public function store(StoreRoleRequest $request)
    {
        $role = $this->service->create($request->toDTO());

        return new RoleResource($role);
    }

    // PUT /api/roles/{id}
    public function update(UpdateRoleRequest $request, int $id)
    {
        $role    = $this->service->findOrFail($id);
        $updated = $this->service->update($role, $request->toDTO());

        return new RoleResource($updated);
    }

    // DELETE /api/roles/{id}
    public function destroy(int $id)
    {
        $role = $this->service->findOrFail($id);
        $this->service->delete($role);

        return response()->json(['message' => 'Papel removido com sucesso.']);
    }

    // ─────────────────────────────────────
    // Gerenciamento de permissions no role
    // ─────────────────────────────────────

    // PUT /api/roles/{id}/permissions
    // Substitui TODAS as permissions do role
    public function syncPermissions(Request $request, int $id)
    {
        $request->validate([
            'permissions'   => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = $this->service->findOrFail($id);
        $ids  = $this->service->resolvePermissionIds($request->permissions);
        $updated = $this->service->syncPermissions($role, $ids);

        return new RoleResource($updated);
    }

    // POST /api/roles/{id}/permissions
    // Adiciona permissions SEM remover as existentes
    public function attachPermissions(Request $request, int $id)
    {
        $request->validate([
            'permissions'   => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = $this->service->findOrFail($id);
        $ids  = $this->service->resolvePermissionIds($request->permissions);
        $updated = $this->service->attachPermissions($role, $ids);

        return new RoleResource($updated);
    }

    // DELETE /api/roles/{id}/permissions
    // Remove permissions específicas do role
    public function detachPermissions(Request $request, int $id)
    {
        $request->validate([
            'permissions'   => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = $this->service->findOrFail($id);
        $ids  = $this->service->resolvePermissionIds($request->permissions);
        $updated = $this->service->detachPermissions($role, $ids);

        return new RoleResource($updated);
    }
}
