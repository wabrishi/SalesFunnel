<?php

namespace App\Middleware;

use App\Helpers\Session;
use App\Helpers\Redirect;
use App\Services\RoleService;

class RoleMiddleware implements Middleware
{
    private string $requiredRole;
    public function __construct(string $requiredRole = '') { $this->requiredRole = $requiredRole; }
    public function handle(): bool
    {
        $userId = Session::get('user_id');
        if (!$userId) { Session::flash('error', 'Please log in.'); Redirect::to('/login'); return false; }
        if (empty($this->requiredRole)) return true;

        $roleService = new RoleService();
        if (!$roleService->hasRole($userId, $this->requiredRole)) {
            http_response_code(403); echo "403 Forbidden"; return false;
        }
        return true;
    }
}
