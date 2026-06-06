<?php

namespace App\Middleware;

use App\Helpers\Session;
use App\Helpers\Redirect;
use App\Services\RoleService;

class PermissionMiddleware implements Middleware
{
    private string $requiredPermission;
    public function __construct(string $requiredPermission = '') { $this->requiredPermission = $requiredPermission; }
    public function handle(): bool
    {
        $userId = Session::get('user_id');
        if (!$userId) { Session::flash('error', 'Please log in.'); Redirect::to('/login'); return false; }
        if (empty($this->requiredPermission)) return true;

        $roleService = new RoleService();
        if (!$roleService->hasPermission($userId, $this->requiredPermission)) {
            http_response_code(403); echo "403 Forbidden - Missing permission: {$this->requiredPermission}"; return false;
        }
        return true;
    }
}
