<?php

/** @var \App\Services\Router $router */

// Auth routes
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin'], 'login');
$router->group(['middleware' => [\App\Middleware\CsrfMiddleware::class]], function($router) {
    $router->post('/login', [\App\Controllers\AuthController::class, 'login'], 'login.post');
    $router->post('/logout', [\App\Controllers\AuthController::class, 'logout'], 'logout');
    $router->post('/forgot-password', [\App\Controllers\AuthController::class, 'sendResetLink'], 'password.email');
    $router->post('/reset-password', [\App\Controllers\AuthController::class, 'resetPassword'], 'password.update');
});

$router->get('/forgot-password', [\App\Controllers\AuthController::class, 'showForgotPassword'], 'password.request');
$router->get('/reset-password', [\App\Controllers\AuthController::class, 'showResetPassword'], 'password.reset');

$router->group(['middleware' => [\App\Middleware\CsrfMiddleware::class, \App\Middleware\AuthMiddleware::class]], function($router) {
    $router->get('/', [\App\Controllers\HomeController::class, 'index'], 'home');

    // Users Module
    $router->group(['prefix' => '/users', 'middleware' => [[\App\Middleware\PermissionMiddleware::class, 'manage_users']]], function($router) {
        $router->get('', [\App\Controllers\UserController::class, 'index'], 'users.index');
        $router->get('/create', [\App\Controllers\UserController::class, 'create'], 'users.create');
        $router->post('/create', [\App\Controllers\UserController::class, 'store'], 'users.store');
    });

    // Roles Module
    $router->group(['prefix' => '/roles', 'middleware' => [[\App\Middleware\PermissionMiddleware::class, 'manage_roles']]], function($router) {
        $router->get('', [\App\Controllers\RoleController::class, 'index'], 'roles.index');
        $router->get('/create', [\App\Controllers\RoleController::class, 'create'], 'roles.create');
        $router->post('/create', [\App\Controllers\RoleController::class, 'store'], 'roles.store');
        $router->get('/{id}/edit', [\App\Controllers\RoleController::class, 'edit'], 'roles.edit');
        $router->post('/{id}/edit', [\App\Controllers\RoleController::class, 'update'], 'roles.update');
    });

    // Audit Logs Module
    $router->group(['prefix' => '/audit-logs', 'middleware' => [[\App\Middleware\PermissionMiddleware::class, 'manage_users']]], function($router) {
        $router->get('', [\App\Controllers\AuditLogController::class, 'index'], 'audit_logs.index');
    });

    // Leads Module
    $router->group(['prefix' => '/leads', 'middleware' => [[\App\Middleware\PermissionMiddleware::class, 'view_leads']]], function($router) {
        $router->get('', [\App\Controllers\LeadController::class, 'index'], 'leads.index');
        $router->get('/create', [\App\Controllers\LeadController::class, 'create'], 'leads.create');
        $router->post('/create', [\App\Controllers\LeadController::class, 'store'], 'leads.store');
        $router->get('/{id}/edit', [\App\Controllers\LeadController::class, 'edit'], 'leads.edit');
        $router->post('/{id}/edit', [\App\Controllers\LeadController::class, 'update'], 'leads.update');
        $router->post('/{id}/delete', [\App\Controllers\LeadController::class, 'delete'], 'leads.delete');
    });
});
