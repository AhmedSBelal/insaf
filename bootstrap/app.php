<?php

use App\Http\Middleware\CharityApprovedMiddleware;
use App\Http\Middleware\SupplierApprovedMiddleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
        Route::middleware(['auth:sanctum' , 'supplier.approve'])
            ->prefix('api/supplier')
            ->as('supplier.')
            ->group(base_path('routes/API/Supplier.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(aliases: [
            'charity.approve' => CharityApprovedMiddleware::class,
            'supplier.approve' => SupplierApprovedMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
