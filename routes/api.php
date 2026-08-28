<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────
// ROTAS PÚBLICAS
// ─────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ─────────────────────────────────────
// ROTAS PROTEGIDAS
// ─────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // ── Permissions (apenas admin) ────────────────────
    Route::apiResource('permissions', PermissionController::class)
        ->middleware('permission:users.manage');

    // ── Roles (apenas admin) ──────────────────────────
    Route::apiResource('roles', RoleController::class)
        ->middleware('permission:users.manage');

    Route::put('roles/{id}/permissions',    [RoleController::class, 'syncPermissions'])
        ->middleware('permission:users.manage');
    Route::post('roles/{id}/permissions',   [RoleController::class, 'attachPermissions'])
        ->middleware('permission:users.manage');
    Route::delete('roles/{id}/permissions', [RoleController::class, 'detachPermissions'])
        ->middleware('permission:users.manage');

    // ── Users (apenas admin) ──────────────────────────
    Route::apiResource('users', UserController::class)
        ->middleware('permission:users.manage');

    Route::put('users/{id}/roles',    [UserController::class, 'syncRoles'])
        ->middleware('permission:users.manage');
    Route::post('users/{id}/roles',   [UserController::class, 'attachRoles'])
        ->middleware('permission:users.manage');
    Route::delete('users/{id}/roles', [UserController::class, 'detachRoles'])
        ->middleware('permission:users.manage');

    // ── Categories ────────────────────────────────────
    Route::apiResource('categories', CategoryController::class)
        ->middleware('permission:categories.manage');

    // ── Brands ────────────────────────────────────
    Route::apiResource('brands', BrandController::class)
    ->middleware('permission:categories.manage');

    Route::apiResource('suppliers', SupplierController::class)
    ->middleware('permission:suppliers.manage');
});
