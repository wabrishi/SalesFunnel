<div class="mb-6"><h2 class="text-2xl font-bold text-gray-800">Edit Follow-Up</h2></div>
<div class="bg-white shadow rounded-lg p-6 max-w-2xl">
    <form action="/follow-ups/<?= $followUp['id'] ?>/edit" method="POST">
        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <?php foreach(['Call', 'Meeting', 'WhatsApp', 'Email', 'Site Visit', 'Demo', 'Proposal Discussion'] as $type): ?>
                        <option value="<?= $type ?>" <?= $followUp['type'] === $type ? 'selected' : '' ?>><?= $type ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <?php foreach(['Pending', 'Completed', 'Missed', 'Cancelled'] as $status): ?>
                        <option value="<?= $status ?>" <?= $followUp['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="follow_up_date" value="<?= e($followUp['follow_up_date']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Time</label>
                <input type="time" name="follow_up_time" value="<?= e($followUp['follow_up_time']) ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Remarks</label>
                <input type="text" name="remarks" value="<?= e($followUp['remarks']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Assign To</label>
                <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <?php foreach($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= $followUp['assigned_to'] == $user['id'] ? 'selected' : '' ?>><?= e($user['first_name'] . ' ' . $user['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="/leads/<?= $followUp['lead_id'] ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700">Update Follow-Up</button>
        </div>
    </form>
</div>
