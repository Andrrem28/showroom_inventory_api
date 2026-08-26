<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Não autenticado.',
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'message' => 'Conta desativada.',
            ], 403);
        }

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Acesso negado. Você não tem permissão para realizar esta ação.',
        ], 403);
    }
}
