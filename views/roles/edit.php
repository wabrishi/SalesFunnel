<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Edit Role: <?= e($role['name']) ?></h2>
</div>

<div class="bg-white shadow rounded-lg p-6 max-w-3xl">
    <form action="/roles/<?= $role['id'] ?>/edit" method="POST">
        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Role Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?= e($role['name']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <input type="text" name="description" value="<?= e($role['description']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Assign Permissions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 border border-gray-200 rounded-md p-4 bg-gray-50">
                <?php foreach ($permissions as $perm): ?>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="perm_<?= $perm['id'] ?>" name="permissions[]" value="<?= $perm['id'] ?>" type="checkbox" <?= in_array($perm['id'], $rolePermissions) ? 'checked' : '' ?> class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-2 text-sm">
                            <label for="perm_<?= $perm['id'] ?>" class="font-medium text-gray-700"><?= e($perm['name']) ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="/roles" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</a>
            <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update Role</button>
        </div>
    </form>
</div>