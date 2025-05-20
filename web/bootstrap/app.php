<?php

use App\Http\Middleware\AccessControlHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register global middleware
        $middleware->append(AccessControlHeaders::class);

        // Register middleware aliases for Shopify
        $middleware->alias([
            'shopify.auth' => \App\Http\Middleware\EnsureShopifySession::class,
            'shopify.installed' => \App\Http\Middleware\EnsureShopifyInstalled::class,
        ]);

        // Add Shopify middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\CspHeader::class,
        ]);

        // Define API middleware group
        $middleware->api(prepend: [
            // Add any API-specific middleware here
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configure exception handling
        $exceptions->reportable(function (\Throwable $e) {
            // Custom exception reporting
        });
    })->create();
