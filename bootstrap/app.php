<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;



return Application::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    channels: __DIR__.'/../routes/channels.php',
)
->withMiddleware(function ($middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'petugas' => \App\Http\Middleware\PetugasMiddleware::class,
        'login' => \App\Http\Middleware\LoginMiddleware::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
