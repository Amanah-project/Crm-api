<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Throwable;
use Amanah\Common\Exceptions\ApiExceptionFormatter;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [\Amanah\Common\Http\Middleware\ForceJson::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (Throwable $e, $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            [$body, $status] = ApiExceptionFormatter::format($e);

            return response()->json($body, $status);
        });
    })->create();

return $app;