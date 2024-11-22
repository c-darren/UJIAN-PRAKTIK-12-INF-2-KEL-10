<?php

use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(\Illuminate\Session\Middleware\StartSession::class);
        // $middleware->append(\Illuminate\Session\Middleware\AddQueuedCookies::class); // Jika perlu untuk cookies antri
        $middleware->append(\Illuminate\View\Middleware\ShareErrorsFromSession::class);
        // $middleware->append(\Illuminate\Routing\Middleware\SubstituteBindings::class);

        return [
            'api' => [],
            'web' => [
                'log' => \App\Http\Middleware\LogUserAccess::class,
                'checkUserRole' => \App\Http\Middleware\CheckUserRole::class,
                'ClearCookiesOnCSRFError' => \App\Http\Middleware\ClearCookiesOnCSRFError::class,
            ]
        ];
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->respond(function (Response $response) {
        //     if ($response->getStatusCode() === 403) {
        //         return redirect()->route('login')->with('error', 'The token is invalid or expired');
        //     }
     
        //     return $response;
        // });
    })
    ->create();
