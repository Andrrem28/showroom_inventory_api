<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'is_active'   => $this->is_active,
            'roles'       => $this->whenLoaded('roles', fn() =>
                $this->roles->map(fn($role) => [
                    'id'          => $role->id,
                    'name'        => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                ])
            ),
            'created_at'  => $this->created_at->format('d/m/Y H:i'),
            'updated_at'  => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
