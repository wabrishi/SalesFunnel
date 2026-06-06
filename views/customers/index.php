<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manage Customers</h2>
    <div class="mt-4 sm:mt-0 flex space-x-3">
        <a href="/customers/create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Add New Customer</a>
    </div>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer / Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Industry</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="/customers/<?= $customer['id'] ?>" class="text-sm font-bold text-indigo-600 hover:text-indigo-900"><?= e($customer['name']) ?></a>
                        <div class="text-xs text-gray-500 mt-1"><?= e($customer['company_name'] ?: 'N/A') ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?= e($customer['email'] ?: 'No Email') ?></div>
                        <div class="text-xs text-gray-500"><?= e($customer['phone'] ?: 'No Phone') ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($customer['industry'] ?: 'Not Specified') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($customer['assigned_name'] ?: 'Unassigned') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($customers)): ?>
                    <tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No customers found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
