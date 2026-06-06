<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manage Leads</h2>
    <div class="mt-4 sm:mt-0 flex space-x-3">
        <a href="/leads/create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Add New Lead</a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white shadow rounded-lg p-4 mb-6">
    <form method="GET" action="/leads" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-700">Search</label>
            <input type="text" name="search" value="<?= e($filters['search']) ?>" placeholder="Name, Email, Company" class="mt-1 block w-full text-sm border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 block w-full text-sm border-gray-300 rounded-md">
                <option value="">All Statuses</option>
                <?php foreach(['New', 'Contacted', 'Qualified', 'Unqualified', 'Converted', 'Lost'] as $status): ?>
                    <option value="<?= $status ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700">Priority</label>
            <select name="priority" class="mt-1 block w-full text-sm border-gray-300 rounded-md">
                <option value="">All Priorities</option>
                <option value="High" <?= $filters['priority'] === 'High' ? 'selected' : '' ?>>High</option>
                <option value="Medium" <?= $filters['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                <option value="Low" <?= $filters['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700">Assigned To</label>
            <select name="assigned_to" class="mt-1 block w-full text-sm border-gray-300 rounded-md">
                <option value="">Anyone</option>
                <?php foreach($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= $filters['assigned_to'] == $user['id'] ? 'selected' : '' ?>><?= e($user['first_name'] . ' ' . $user['last_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-gray-800 text-white py-2 px-4 rounded-md text-sm hover:bg-gray-700">Filter</button>
        </div>
    </form>
</div>

<!-- List -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status & Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($leads as $lead): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?= e($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                        <div class="text-sm text-gray-500"><?= e($lead['company'] ?: 'No Company') ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?= e($lead['email'] ?: 'No Email') ?></div>
                        <div class="text-sm text-gray-500"><?= e($lead['phone'] ?: 'No Phone') ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mb-1"><?= e($lead['status']) ?></span><br>
                        <?php
                            $pColor = $lead['priority'] === 'High' ? 'text-red-600' : ($lead['priority'] === 'Medium' ? 'text-yellow-600' : 'text-green-600');
                        ?>
                        <span class="text-xs font-medium <?= $pColor ?>"><?= e($lead['priority']) ?> Priority</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($lead['assigned_name'] ?: 'Unassigned') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="/leads/<?= $lead['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form method="POST" action="/leads/<?= $lead['id'] ?>/delete" class="inline" onsubmit="return confirm('Are you sure you want to delete this lead?');">
                            <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($leads)): ?>
                    <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No leads found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
