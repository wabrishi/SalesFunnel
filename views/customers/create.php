<div class="mb-6"><h2 class="text-2xl font-bold text-gray-800">Create New Customer</h2></div>
<div class="bg-white shadow rounded-lg p-6 max-w-3xl">
    <form action="/customers/create" method="POST">
        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Display Name / Alias <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="company_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Primary Email</label>
                <input type="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Primary Phone Number</label>
                <input type="text" name="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">GST Number</label>
                <input type="text" name="gst_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Industry</label>
                <input type="text" name="industry" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Assign To</label>
                <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Unassigned</option>
                    <?php foreach($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= e($user['first_name'] . ' ' . $user['last_name']) ?> (<?= e($user['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="/customers" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700">Save Customer</button>
        </div>
    </form>
</div>
