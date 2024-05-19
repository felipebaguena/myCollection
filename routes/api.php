<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameDetailsController;
use App\Http\Middleware\VerifyToken;

Route::get('/test', function () {
    return response()->json(['message' => '¡Ruta de prueba API funciona correctamente!']);
});

// Ruta para crear usuarios
Route::post('/users', [UserController::class, 'createUser']);

// Ruta para login
Route::post('/login', [AuthController::class, 'login']);

// Agrupamos las rutas que requieren el middleware 'verify.token'
Route::middleware([VerifyToken::class])->group(function () {
    // Ruta para actualizar nik y contraseña de usuario (contraseña anterior requerida)
    Route::put('/users/{userId}', [UserController::class, 'updateUser']);
    
    // Ruta ver todos los usuarios
    Route::get('/users', [UserController::class, 'getAllUsers']);
    
    // Ruta para eliminar un usuario
    Route::delete('/users/{userId}', [UserController::class, 'deleteUser']);
    
    // Ruta para obtener roles
    Route::get('/roles', [RoleController::class, 'index']);
    
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
    
    // Ruta para obtener todos los juegos
    Route::post('/games/filter', [GameController::class, 'getAllGames']);
    
    // Ruta para detalles de juego
    Route::get('/games/{id}', [GameDetailsController::class, 'show']);
    
    // Ruta para eliminar un juego
    Route::delete('/games/{id}', [GameController::class, 'destroy']);
    
    // Ruta para añadir un juego a la librería de un usuario
    Route::post('/users/{userId}/games', [UserController::class, 'addGameToLibrary']);
    
    // Ruta para eliminar un juego de la librería de un usuario
    Route::delete('/users/{userId}/games/{gameId}', [UserController::class, 'removeGameFromLibrary']);
    
    // Ruta para ver la librería de un usuario
    Route::get('/users/{userId}/library', [UserController::class, 'getUserLibrary']);
});
