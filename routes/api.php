<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\StorageLocationController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
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

    // ── Permissions ───────────────────────────────────
    Route::apiResource('permissions', PermissionController::class)
        ->middleware('permission:users.manage');

    // ── Roles ─────────────────────────────────────────
    Route::apiResource('roles', RoleController::class)
        ->middleware('permission:users.manage');

    Route::put('roles/{id}/permissions',    [RoleController::class, 'syncPermissions'])
        ->middleware('permission:users.manage');
    Route::post('roles/{id}/permissions',   [RoleController::class, 'attachPermissions'])
        ->middleware('permission:users.manage');
    Route::delete('roles/{id}/permissions', [RoleController::class, 'detachPermissions'])
        ->middleware('permission:users.manage');

    // ── Users ─────────────────────────────────────────
    Route::apiResource('users', UserController::class)
        ->middleware('permission:users.manage');

    Route::put('users/{user}/roles',    [UserController::class, 'syncRoles'])
        ->middleware('permission:users.manage');
    Route::post('users/{user}/roles',   [UserController::class, 'attachRoles'])
        ->middleware('permission:users.manage');
    Route::delete('users/{user}/roles', [UserController::class, 'detachRoles'])
        ->middleware('permission:users.manage');

    // ── Categories ────────────────────────────────────
    Route::apiResource('categories', CategoryController::class)
        ->middleware('permission:categories.manage');

    // ── Brands ────────────────────────────────────────
    Route::apiResource('brands', BrandController::class)
        ->middleware('permission:categories.manage');

    // ── Suppliers ─────────────────────────────────────
    Route::apiResource('suppliers', SupplierController::class)
        ->middleware('permission:suppliers.manage');

    // ── Storage Locations ─────────────────────────────
    Route::apiResource('storage-locations', StorageLocationController::class)
        ->middleware('permission:storage-locations.manage');

    // ── Products ──────────────────────────────────────
    Route::get('products/low-stock', [ProductController::class, 'lowStock'])
        ->middleware('permission:products.manage');

    Route::apiResource('products', ProductController::class)
        ->middleware('permission:products.manage');

    // ── Stock Movements ───────────────────────────────
    Route::apiResource('stock-movements', StockMovementController::class)
        ->only(['index', 'show', 'store'])
        ->middleware('permission:stock.manage');

    // Histórico por produto
    Route::get('products/{product}/stock-movements', [StockMovementController::class, 'byProduct'])
        ->middleware('permission:stock.manage');

});
