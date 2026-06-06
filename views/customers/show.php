<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">
        Customer: <?= e($customer['name']) ?>
    </h2>
    <div>
        <a href="/customers/<?= $customer['id'] ?>/edit" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300">Edit Customer</a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Left Column: Customer Profile & Contacts -->
    <div class="xl:col-span-1 space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Master Details</h3>
            <div class="space-y-3 text-sm">
                <p><span class="font-medium text-gray-500 w-24 inline-block">Company:</span> <?= e($customer['company_name'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Email:</span> <?= e($customer['email'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Phone:</span> <?= e($customer['phone'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Industry:</span> <?= e($customer['industry'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">GST No:</span> <?= e($customer['gst_number'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Assigned To:</span> <?= e($customer['assigned_name'] ?: 'Unassigned') ?></p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h3 class="text-lg font-bold text-gray-800">Contacts</h3>
            </div>

            <?php if (empty($customer['contacts'])): ?>
                <p class="text-sm text-gray-500 italic mb-4">No contacts added.</p>
            <?php else: ?>
                <div class="space-y-4 mb-4">
                    <?php foreach ($customer['contacts'] as $contact): ?>
                        <div class="border rounded-md p-3 <?= $contact['is_primary'] ? 'border-indigo-300 bg-indigo-50' : 'bg-gray-50' ?>">
                            <div class="font-bold text-gray-800 text-sm flex justify-between">
                                <?= e($contact['first_name'] . ' ' . $contact['last_name']) ?>
                                <?php if ($contact['is_primary']): ?>
                                    <span class="text-xs bg-indigo-200 text-indigo-800 px-2 py-0.5 rounded-full">Primary</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-xs text-gray-500 mb-1"><?= e($contact['designation'] ?: 'No Designation') ?></div>
                            <div class="text-xs text-gray-700">Email: <?= e($contact['email'] ?: 'N/A') ?></div>
                            <div class="text-xs text-gray-700">Phone: <?= e($contact['phone'] ?: 'N/A') ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="/customers/<?= $customer['id'] ?>/contacts" method="POST" class="border-t pt-4 space-y-3">
                <h4 class="text-sm font-bold text-gray-700">Add New Contact</h4>
                <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="first_name" placeholder="First Name *" required class="block w-full border-gray-300 rounded-md text-sm">
                    <input type="text" name="last_name" placeholder="Last Name *" required class="block w-full border-gray-300 rounded-md text-sm">
                    <input type="email" name="email" placeholder="Email" class="block w-full border-gray-300 rounded-md text-sm">
                    <input type="text" name="phone" placeholder="Phone" class="block w-full border-gray-300 rounded-md text-sm">
                    <input type="text" name="designation" placeholder="Designation" class="col-span-2 block w-full border-gray-300 rounded-md text-sm">
                </div>
                <div class="flex items-center text-sm">
                    <input type="checkbox" name="is_primary" id="is_primary" class="mr-2 border-gray-300 rounded">
                    <label for="is_primary" class="text-gray-700">Mark as Primary Contact</label>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md text-sm hover:bg-indigo-700">Add Contact</button>
            </form>
        </div>
    </div>

    <!-- Right Column: Timeline & Opportunities -->
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Linked Opportunities</h3>

            <?php if (empty($customer['opportunities'])): ?>
                <p class="text-sm text-gray-500 italic">No opportunities linked to this customer.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opportunity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stage</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($customer['opportunities'] as $op): ?>
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-indigo-600"><?= e($op['name']) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900">$<?= number_format($op['value'], 2) ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= e($op['stage']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500"><?= date('M d, Y', strtotime($op['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Future Phase: Communication Timeline Template Placeholder -->
        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-6 text-center">
            <h3 class="text-md font-medium text-gray-600 mb-2">Communication Timeline (Coming Soon)</h3>
            <p class="text-sm text-gray-400">Phase 7 will inject the Activity & Communication Center history here.</p>
        </div>
    </div>
</div>
