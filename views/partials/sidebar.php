<div class="bg-gray-800 text-white w-64 flex-shrink-0 flex flex-col transition-all duration-300">
    <div class="h-16 flex items-center justify-center border-b border-gray-700">
        <h1 class="text-xl font-bold tracking-wider">CRM</h1>
    </div>
    <div class="flex-1 overflow-y-auto py-4">
        <nav class="space-y-1 px-2">
            <a href="/" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white text-gray-300">Dashboard</a>
            <a href="/leads" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white text-gray-300">Leads</a>
            <?php if ((new \App\Services\RoleService())->hasPermission(\App\Helpers\Session::get('user_id'), 'view_kanban')): ?>
                <a href="/opportunities" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white text-gray-300">Kanban Board</a>
            <?php endif; ?>
            <?php if ((new \App\Services\RoleService())->hasPermission(\App\Helpers\Session::get('user_id'), 'manage_customers')): ?>
                <a href="/customers" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white text-gray-300">Customers</a>
            <?php endif; ?>
            <?php if ((new \App\Services\RoleService())->hasPermission(\App\Helpers\Session::get('user_id'), 'manage_users')): ?>
                <a href="/users" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white text-gray-300">Users</a>
                <a href="/roles" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white text-gray-300">Roles</a>
                <a href="/audit-logs" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-gray-700 hover:text-white text-gray-300">Audit Logs</a>
            <?php endif; ?>
        </nav>
    </div>
</div>
