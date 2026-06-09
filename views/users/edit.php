<div class="mb-6"><h2 class="text-2xl font-bold text-gray-800">Edit User</h2></div>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl">
    <form action="/users/<?= $user['id'] ?>/edit" method="POST">
        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
            <div><label class="block text-sm font-medium text-gray-700">First Name</label><input type="text" name="first_name" value="<?= e($user['first_name']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700">Last Name</label><input type="text" name="last_name" value="<?= e($user['last_name']) ?>" class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></div>
        </div>
        <div class="mb-6"><label class="block text-sm font-medium text-gray-700">Email Address</label><input type="email" name="email" value="<?= e($user['email']) ?>" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></div>
        <div class="mb-6"><label class="block text-sm font-medium text-gray-700">Password (Leave blank to keep current)</label><input type="password" name="password" class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></div>
        <div class="mb-6"><label class="block text-sm font-medium text-gray-700 mb-2">Assign Roles</label><div class="space-y-2 border border-gray-200 rounded-md p-4">
            <?php foreach ($roles as $role): ?>
                <div class="flex items-start"><div class="flex items-center h-5"><input name="roles[]" value="<?= $role['id'] ?>" type="checkbox" <?= in_array($role['id'], $assignedRoleIds) ? 'checked' : '' ?> class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"></div><div class="ml-3 text-sm"><label class="font-medium text-gray-700"><?= e($role['name']) ?></label></div></div>
            <?php endforeach; ?>
        </div></div>
        <div class="mb-6"><label class="block text-sm font-medium text-gray-700">Status</label><select name="status" class="mt-1 block w-full bg-white border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select></div>
        <div class="flex justify-end space-x-3"><a href="/users" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</a><button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update User</button></div>
    </form>
</div>