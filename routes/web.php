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
        $router->get('/{id}/edit', [\App\Controllers\UserController::class, 'edit'], 'users.edit');
        $router->post('/{id}/edit', [\App\Controllers\UserController::class, 'update'], 'users.update');
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
        $router->get('/{id}', [\App\Controllers\LeadController::class, 'show'], 'leads.show');
        $router->post('/bulk-assign', [\App\Controllers\LeadController::class, 'bulkAssign'], 'leads.bulk.assign');
        $router->get('/{id}/edit', [\App\Controllers\LeadController::class, 'edit'], 'leads.edit');
        $router->post('/{id}/edit', [\App\Controllers\LeadController::class, 'update'], 'leads.update');
        $router->post('/{id}/delete', [\App\Controllers\LeadController::class, 'delete'], 'leads.delete');

        // Follow-Up nested routes
        $router->post('/{id}/follow-ups', [\App\Controllers\FollowUpController::class, 'store'], 'follow_ups.store');
    });

    // Follow-Ups Global operations
    $router->group(['prefix' => '/follow-ups', 'middleware' => [[\App\Middleware\PermissionMiddleware::class, 'manage_follow_ups']]], function($router) {
        $router->get('', [\App\Controllers\FollowUpController::class, 'index'], 'follow_ups.index');
        $router->get('/{id}/edit', [\App\Controllers\FollowUpController::class, 'edit'], 'follow_ups.edit');
        $router->post('/{id}/edit', [\App\Controllers\FollowUpController::class, 'update'], 'follow_ups.update');
        $router->post('/{id}/complete', [\App\Controllers\FollowUpController::class, 'complete'], 'follow_ups.complete');
        $router->post('/{id}/delete', [\App\Controllers\FollowUpController::class, 'delete'], 'follow_ups.delete');
    });

    // Opportunities & Kanban Module
    $router->group(['prefix' => '/opportunities', 'middleware' => [[\App\Middleware\PermissionMiddleware::class, 'manage_opportunities']]], function($router) {
        $router->get('', [\App\Controllers\OpportunityController::class, 'index'], 'opportunities.index');
        $router->post('/convert/{id}', [\App\Controllers\OpportunityController::class, 'convertLead'], 'opportunities.convert');
        $router->post('/{id}/stage', [\App\Controllers\OpportunityController::class, 'updateStage'], 'opportunities.stage.update');
    });

    // Customers Module
    $router->group(['prefix' => '/customers', 'middleware' => [[\App\Middleware\PermissionMiddleware::class, 'manage_customers']]], function($router) {
        $router->get('', [\App\Controllers\CustomerController::class, 'index'], 'customers.index');
        $router->get('/create', [\App\Controllers\CustomerController::class, 'create'], 'customers.create');
        $router->post('/create', [\App\Controllers\CustomerController::class, 'store'], 'customers.store');
        $router->get('/{id}', [\App\Controllers\CustomerController::class, 'show'], 'customers.show');
        $router->get('/{id}/edit', [\App\Controllers\CustomerController::class, 'edit'], 'customers.edit');
        $router->post('/{id}/edit', [\App\Controllers\CustomerController::class, 'update'], 'customers.update');
        $router->post('/{id}/contacts', [\App\Controllers\CustomerController::class, 'storeContact'], 'customers.contacts.store');
    });
});
