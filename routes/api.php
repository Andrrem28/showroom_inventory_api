<?php

use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

# Remover isso apos criar o usuário e a autenticação
// Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('roles', RoleController::class);
// });
