<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────
// ROTAS PÚBLICAS
// ─────────────────────────────────────
// Route::post('/login', [AuthController::class, 'login']);

// ─────────────────────────────────────
// ROTAS PROTEGIDAS
// ─────────────────────────────────────
// Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/me',      [AuthController::class, 'me']);
    // ── Permissions ───────────────────────────────────
    Route::apiResource('permissions', PermissionController::class);
    // ── Roles ─────────────────────────────────────────
    Route::apiResource('roles', RoleController::class);
    // ── Ligação Role <-> Permissions ──────────────────
    // Substitui TODAS as permissions de um role
    Route::put('roles/{id}/permissions', [RoleController::class, 'syncPermissions']);

    // Adiciona permissions sem remover as existentes
    Route::post('roles/{id}/permissions', [RoleController::class, 'attachPermissions']);
    // Remove permissions específicas de um role
    Route::delete('roles/{id}/permissions', [RoleController::class, 'detachPermissions']);
// });
