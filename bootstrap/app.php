<?php

use Illuminate\Foundation\Application;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'login/*',
            'register/*',
        ]);
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'role.redirect' => \App\Http\Middleware\RedirectIfAuthenticatedByRole::class,
            'has.entreprise' => \App\Http\Middleware\RedirectIfNoEntreprise::class,
            'plan.flow' => \App\Http\Middleware\PlanFlowMiddleware::class,
            'instance.limit' => \App\Http\Middleware\InstanceLimitMiddleware::class,
            'registration.flow' => \App\Http\Middleware\RedirectAfterRegistration::class,
            'profile.complete' => \App\Http\Middleware\CheckProfileComplete::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
