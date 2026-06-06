<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">System Audit Logs</h2>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e(date('M d, Y H:i:s', strtotime($log['created_at']))) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?php if ($log['user_id']): ?>
                            <?= e($log['first_name'] . ' ' . $log['last_name']) ?> <span class="text-xs text-gray-500">(<?= e($log['email']) ?>)</span>
                        <?php else: ?>
                            System
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?= e($log['action']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($log['entity_type']) ?> #<?= e($log['entity_id']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                        <?php if ($log['old_values'] || $log['new_values']): ?>
                            <button onclick='alert(
                                "Old: \n" + <?= json_encode($log['old_values'] ?? "None", JSON_HEX_APOS | JSON_HEX_QUOT) ?> +
                                "\n\nNew: \n" + <?= json_encode($log['new_values'] ?? "None", JSON_HEX_APOS | JSON_HEX_QUOT) ?>
                            )' class="text-indigo-600 hover:text-indigo-900 text-xs">View Diff</button>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No logs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
