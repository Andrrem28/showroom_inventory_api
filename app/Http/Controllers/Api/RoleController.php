<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // GET /api/roles
    public function index()
    {
        // $roles = Role::with('permissions')->get();
        $roles = Role::all();

        return response()->json($roles);
    }

    // GET /api/roles/{id}
    public function show(int $id)
    {

        # Quando criar o model de Permissions, desmontar esta linha e testar.
        // $role = Role::with('permissions')->findOrFail($id);
        $role = Role::where('id', '=', $id)->findOrFail($id);

        return response()->json($role);
    }

    // POST /api/roles
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string|max:255',
        ]);

        $role = Role::create($data);

        return response()->json($role, 201);
    }

    // PUT /api/roles/{id}
    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:50|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:255',
        ]);

        $role->update($data);

        return response()->json($role);
    }

    // DELETE /api/roles/{id}
    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Papel removido com sucesso.']);
    }
}
