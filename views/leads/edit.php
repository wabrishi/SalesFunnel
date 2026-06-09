<div class="mb-6"><h2 class="text-2xl font-bold text-gray-800">Edit Lead: <?= e($lead['first_name'] . ' ' . $lead['last_name']) ?></h2></div>
<div class="bg-white shadow rounded-lg p-6 max-w-3xl">
    <form action="/leads/<?= $lead['id'] ?>/edit" method="POST">
        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" value="<?= e($lead['first_name']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                <input type="text" name="last_name" value="<?= e($lead['last_name']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" value="<?= e($lead['email']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone" value="<?= e($lead['phone']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company</label>
                <input type="text" name="company" value="<?= e($lead['company']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Lead Source</label>
                <input type="text" name="source" value="<?= e($lead['source']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <?php foreach(['New', 'Contacted', 'Qualified', 'Unqualified', 'Converted', 'Lost'] as $status): ?>
                        <option value="<?= $status ?>" <?= $lead['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Priority</label>
                <select name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <?php foreach(['High', 'Medium', 'Low'] as $priority): ?>
                        <option value="<?= $priority ?>" <?= $lead['priority'] === $priority ? 'selected' : '' ?>><?= $priority ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Assign To</label>
                <?php
                    $roleSvc = new \App\Services\RoleService();
                    $canAssign = $roleSvc->hasRole(\App\Helpers\Session::get('user_id'), 'Admin') || $roleSvc->hasRole(\App\Helpers\Session::get('user_id'), 'Sales Manager');
                ?>
                <?php if ($canAssign): ?>
                    <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Unassigned</option>
                        <?php foreach($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= $lead['assigned_to'] == $user['id'] ? 'selected' : '' ?>><?= e($user['first_name'] . ' ' . $user['last_name']) ?> (<?= e($user['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input type="hidden" name="assigned_to" value="<?= $lead['assigned_to'] ?>">
                    <p class="mt-1 p-2 bg-gray-100 border border-gray-200 rounded-md text-sm text-gray-600">
                        <?= e($lead['assigned_name'] ?: 'Unassigned') ?> (You do not have permission to reassign)
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="/leads" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700">Update Lead</button>
        </div>
    </form>
</div>
