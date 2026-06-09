<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Global Follow-Ups</h2>
</div>

<div class="bg-white shadow rounded-lg p-4 mb-6">
    <form method="GET" action="/follow-ups" class="flex items-end space-x-4">
        <div>
            <label class="block text-xs font-medium text-gray-700">Filter View</label>
            <select name="filter" class="mt-1 block w-48 text-sm border-gray-300 rounded-md">
                <option value="">All Follow-Ups</option>
                <option value="due_today" <?= $filter === 'due_today' ? 'selected' : '' ?>>Due Today</option>
                <option value="overdue" <?= $filter === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                <option value="missed" <?= $filter === 'missed' ? 'selected' : '' ?>>Missed</option>
                <option value="completed_today" <?= $filter === 'completed_today' ? 'selected' : '' ?>>Completed Today</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md text-sm hover:bg-indigo-700">Apply Filter</button>
    </form>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($followUps as $fu): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= date('M d, Y', strtotime($fu['follow_up_date'])) ?> <br>
                        <span class="text-xs text-gray-500"><?= date('h:i A', strtotime($fu['follow_up_time'])) ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="/leads/<?= $fu['lead_id'] ?>" class="text-sm font-bold text-indigo-600 hover:text-indigo-900"><?= e($fu['lead_name'] ?? 'View Lead') ?></a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($fu['type']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                            $statusColor = 'bg-gray-100 text-gray-800';
                            if ($fu['status'] === 'Completed') $statusColor = 'bg-green-100 text-green-800';
                            if ($fu['status'] === 'Missed') $statusColor = 'bg-red-100 text-red-800';
                            if ($fu['status'] === 'Pending') $statusColor = 'bg-yellow-100 text-yellow-800';
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusColor ?>"><?= e($fu['status']) ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($fu['assigned_name']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <?php if ($fu['status'] === 'Pending'): ?>
                            <form method="POST" action="/follow-ups/<?= $fu['id'] ?>/complete" class="inline">
                                <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                                <button type="submit" class="text-green-600 hover:text-green-900">Complete</button>
                            </form>
                        <?php endif; ?>
                        <a href="/follow-ups/<?= $fu['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($followUps)): ?>
                    <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No follow-ups found matching criteria.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
