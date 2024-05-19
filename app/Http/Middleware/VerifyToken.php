<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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

        // Verificar token
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
