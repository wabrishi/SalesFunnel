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
<form method="POST" action="/leads/bulk-assign" id="bulkAssignForm">
<?= \App\Middleware\CsrfMiddleware::csrfField() ?>

<?php
$roleSvc = new \App\Services\RoleService();
$canAssign = $roleSvc->hasRole(\App\Helpers\Session::get('user_id'), 'Admin') || $roleSvc->hasRole(\App\Helpers\Session::get('user_id'), 'Sales Manager');
if ($canAssign && !empty($leads)):
?>
<div class="bg-white shadow rounded-lg p-4 mb-4 flex items-center justify-between border-t-4 border-indigo-500">
    <div class="text-sm font-medium text-gray-700">Bulk Assignment</div>
    <div class="flex items-center space-x-2">
        <select name="assigned_to" class="text-sm border-gray-300 rounded-md">
            <option value="">Unassigned</option>
            <?php foreach($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md text-sm hover:bg-indigo-700">Assign Selected</button>
    </div>
</div>
<?php endif; ?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <?php if ($canAssign): ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50">
                    </th>
                    <?php endif; ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status & Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Follow-Up</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Health</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($leads as $lead): ?>
                <?php $health = \App\Helpers\LeadHealth::calculateScore($lead); ?>
                <tr>
                    <?php if ($canAssign): ?>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="lead_ids[]" value="<?= $lead['id'] ?>" class="lead-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50">
                    </td>
                    <?php endif; ?>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="/leads/<?= $lead['id'] ?>" class="text-sm font-bold text-indigo-600 hover:text-indigo-900"><?= e($lead['first_name'] . ' ' . $lead['last_name']) ?></a>
                        <div class="text-xs text-gray-500 mt-1"><?= e($lead['company'] ?: 'No Company') ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?= e($lead['email'] ?: 'No Email') ?></div>
                        <div class="text-xs text-gray-500"><?= e($lead['phone'] ?: 'No Phone') ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mb-1"><?= e($lead['status']) ?></span><br>
                        <?php
                            $pColor = $lead['priority'] === 'High' ? 'text-red-600' : ($lead['priority'] === 'Medium' ? 'text-yellow-600' : 'text-green-600');
                        ?>
                        <span class="text-xs font-medium <?= $pColor ?>"><?= e($lead['priority']) ?> Priority</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                            $indicatorHtml = '<span class="text-xs text-gray-500 italic">No Follow-Up</span>';
                            if ($lead['next_follow_up']) {
                                $nextTime = strtotime($lead['next_follow_up']);
                                $diff = $nextTime - time();

                                if ($diff < 0) {
                                    $color = 'bg-red-100 text-red-800'; // Overdue
                                    $label = 'Overdue';
                                } elseif ($diff <= 7200) {
                                    $color = 'bg-orange-100 text-orange-800'; // Due in < 2 hours
                                    $label = 'Due Soon';
                                } elseif (date('Y-m-d', $nextTime) === date('Y-m-d')) {
                                    $color = 'bg-yellow-100 text-yellow-800'; // Due Today
                                    $label = 'Due Today';
                                } else {
                                    $color = 'bg-green-100 text-green-800'; // Future
                                    $label = 'Scheduled';
                                }

                                $dateStr = date('M d, g:i A', $nextTime);
                                $indicatorHtml = "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full {$color}'>{$label}</span><div class='text-xs text-gray-500 mt-1'>{$dateStr}</div>";
                            }
                        ?>
                        <?= $indicatorHtml ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($lead['assigned_name'] ?: 'Unassigned') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded <?= $health['color'] ?>">
                            <?= e($health['label']) ?> (<?= $health['score'] ?>)
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <form method="POST" action="/leads/<?= $lead['id'] ?>/delete" class="inline" onsubmit="return confirm('Are you sure you want to delete this lead?');">
                            <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($leads)): ?>
                    <tr><td colspan="<?= $canAssign ? '7' : '6' ?>" class="px-6 py-4 text-center text-sm text-gray-500">No leads found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</form>

<?php if ($canAssign): ?>
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.lead-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
<?php endif; ?>
