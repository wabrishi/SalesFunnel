<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <a href="/leads" class="block">
        <div class="bg-white shadow rounded-lg p-6 hover:bg-blue-50 transition-colors">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Total Leads</p>
                    <p class="text-lg font-semibold text-gray-700"><?= e($metrics['totalLeads']) ?></p>
                </div>
            </div>
        </div>
    </a>

    <a href="/leads?status=New" class="block">
        <div class="bg-white shadow rounded-lg p-6 hover:bg-green-50 transition-colors">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">New Leads</p>
                    <p class="text-lg font-semibold text-gray-700"><?= e($metrics['newLeads']) ?></p>
                </div>
            </div>
        </div>
    </a>

    <a href="/leads?status=Qualified" class="block">
        <div class="bg-white shadow rounded-lg p-6 hover:bg-yellow-50 transition-colors">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Qualified Leads</p>
                    <p class="text-lg font-semibold text-gray-700"><?= e($metrics['qualifiedLeads']) ?></p>
                </div>
            </div>
        </div>
    </a>

    <a href="/leads?assigned_to=unassigned" class="block">
        <div class="bg-white shadow rounded-lg p-6 hover:bg-red-50 transition-colors">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Unassigned Leads</p>
                    <p class="text-lg font-semibold text-gray-700"><?= e($metrics['unassignedLeads']) ?></p>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="mb-6 mt-8">
    <h2 class="text-2xl font-bold text-gray-800">Follow-Up Overview</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white shadow rounded-lg p-6 border-t-4 border-yellow-400">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Due Today</p>
                <p class="text-lg font-semibold text-gray-700"><?= e($metrics['dueToday']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 border-t-4 border-red-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Overdue</p>
                <p class="text-lg font-semibold text-gray-700"><?= e($metrics['overdue']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 border-t-4 border-gray-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Missed</p>
                <p class="text-lg font-semibold text-gray-700"><?= e($metrics['missed']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 border-t-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Completed Today</p>
                <p class="text-lg font-semibold text-gray-700"><?= e($metrics['completedToday']) ?></p>
            </div>
        </div>
    </div>
</div>