<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;

Route::get('/test', function () {
    return response()->json(['message' => '¡Ruta de prueba API funciona correctamente!']);
});

// Ruta para crear usuarios
Route::post('/users', [UserController::class, 'createUser']);

// Ruta para obtener roles
Route::get('/roles', [RoleController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);