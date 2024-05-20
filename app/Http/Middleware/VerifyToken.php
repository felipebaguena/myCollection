<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Rutas que no requieren verificación de token
        $routesWithoutToken = [
            'login' => 'POST',
            'users' => 'POST'
        ];

        // Verificar si la ruta actual es una de las que no requiere token
        foreach ($routesWithoutToken as $route => $method) {
            if ($request->is($route) && $request->isMethod($method)) {
                return $next($request);
            }
        }

        // Obtener el token del encabezado
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Verificar y autenticar el token
        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Establecer el usuario autenticado
        Auth::setUser($accessToken->tokenable);

        return $next($request);
    }
}
