<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameDetailsController;

Route::get('/test', function () {
    return response()->json(['message' => '¡Ruta de prueba API funciona correctamente!']);
});

// Ruta para crear usuarios
Route::post('/users', [UserController::class, 'createUser']);

// Ruta para obtener roles
Route::get('/roles', [RoleController::class, 'index']);

// Ruta para login
Route::post('/login', [AuthController::class, 'login']);

// Ruta para subir una imagen de un juego
Route::post('/games/{gameId}/images', [ImageController::class, 'uploadImage']);

// Ruta para eliminar una imagen de un juego
Route::delete('/images/{id}', [ImageController::class, 'destroy']);

// Ruta para actualizar la imagen de portada de un juego
Route::post('/games/{gameId}/cover', [ImageController::class, 'updateCoverImage']);

// Ruta para crear un juego
Route::post('/games', [GameController::class, 'store']);

// Ruta para actualizar un juego
Route::put('/games/{id}', [GameController::class, 'update']);

// Ruta para detalles de juego
Route::get('/games/{id}', [GameDetailsController::class, 'show']);

// Ruta para eliminar un juego
Route::delete('/games/{id}', [GameController::class, 'destroy']);
