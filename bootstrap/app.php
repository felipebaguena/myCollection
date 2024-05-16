<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(
            except: ['stripe/*']
        );

        // $middleware->web(append: [
        //     \App\Http\Middleware\EnsureUserIsSubscribed::class,
        // ]);

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        $exceptions->dontReport(\App\Exceptions\MissedFlightException::class);

        $exceptions->report(function (\App\Exceptions\InvalidOrderException $e) {
            // ...
        });
    })
    ->create();
