<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manage Roles</h2>
    <div class="mt-4 sm:mt-0">
        <a href="/roles/create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            Add New Role
        </a>
    </div>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($roles as $role): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= e($role['name']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= e($role['description']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="/roles/<?= $role['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900">Edit Permissions</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
