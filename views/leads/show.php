<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">
        Lead: <?= e($lead['first_name'] . ' ' . $lead['last_name']) ?>
        <?php $health = \App\Helpers\LeadHealth::calculateScore($lead); ?>
        <span class="ml-4 px-3 py-1 text-sm rounded-full font-semibold <?= $health['color'] ?>">
            <?= e($health['label']) ?> (<?= $health['score'] ?>)
        </span>
    </h2>
    <div>
        <a href="/leads/<?= $lead['id'] ?>/edit" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300">Edit Lead</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Lead Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Contact Details</h3>
            <div class="space-y-3 text-sm">
                <p><span class="font-medium text-gray-500 w-24 inline-block">Email:</span> <?= e($lead['email'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Phone:</span> <?= e($lead['phone'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Company:</span> <?= e($lead['company'] ?: 'N/A') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Source:</span> <?= e($lead['source'] ?: 'N/A') ?></p>
            </div>

            <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mt-6 mb-4">Status & Assignment</h3>
            <div class="space-y-3 text-sm">
                <p><span class="font-medium text-gray-500 w-24 inline-block">Status:</span> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><?= e($lead['status']) ?></span></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Priority:</span> <?= e($lead['priority']) ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Assigned To:</span> <?= e($lead['assigned_name'] ?: 'Unassigned') ?></p>
                <p><span class="font-medium text-gray-500 w-24 inline-block">Created By:</span> <?= e($lead['creator_name']) ?></p>
            </div>

            <?php if ($lead['status'] !== 'Converted'): ?>
            <div class="mt-8 border-t pt-4">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Convert to Opportunity</h3>
                <form action="/opportunities/convert/<?= $lead['id'] ?>" method="POST" class="space-y-3">
                    <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                    <div>
                        <input type="text" name="name" placeholder="Opportunity Name *" required class="block w-full border-gray-300 rounded-md text-sm">
                    </div>
                    <div>
                        <input type="number" step="0.01" name="value" placeholder="Expected Value ($)" class="block w-full border-gray-300 rounded-md text-sm">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md text-sm hover:bg-green-700 font-medium">Convert</button>
                </form>
            </div>
            <?php else: ?>
                <div class="mt-8 bg-green-50 border border-green-200 text-green-800 rounded p-3 text-sm font-medium text-center">
                    Lead is Converted.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Timeline & Follow Ups -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Add Follow Up Form -->
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-indigo-500">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Schedule Follow-Up</h3>
            <form action="/leads/<?= $lead['id'] ?>/follow-ups" method="POST">
                <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Type</label>
                        <select name="type" required class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                            <?php foreach(['Call', 'Meeting', 'WhatsApp', 'Email', 'Site Visit', 'Demo', 'Proposal Discussion'] as $type): ?>
                                <option value="<?= $type ?>"><?= $type ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Date</label>
                        <input type="date" name="follow_up_date" required min="<?= date('Y-m-d') ?>" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Time</label>
                        <input type="time" name="follow_up_time" required class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700">Remarks</label>
                        <input type="text" name="remarks" placeholder="Objectives or notes..." class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Assign To</label>
                        <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md text-sm">
                            <?php foreach($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= \App\Helpers\Session::get('user_id') == $user['id'] ? 'selected' : '' ?>><?= e($user['first_name'] . ' ' . $user['last_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md text-sm hover:bg-indigo-700">Schedule</button>
                </div>
            </form>
        </div>

        <!-- Follow-Up Timeline -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Follow-Up History</h3>

            <?php if (empty($followUps)): ?>
                <p class="text-sm text-gray-500 italic">No follow-ups scheduled yet.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($followUps as $fu): ?>
                        <?php
                            // Determine visual priority based on status and date
                            $fuDateTime = strtotime($fu['follow_up_date'] . ' ' . $fu['follow_up_time']);
                            $isOverdue = $fu['status'] === 'Pending' && $fuDateTime < time();
                            $statusColor = 'bg-gray-100 text-gray-800';
                            if ($fu['status'] === 'Completed') $statusColor = 'bg-green-100 text-green-800';
                            if ($fu['status'] === 'Missed' || $isOverdue) $statusColor = 'bg-red-100 text-red-800';
                        ?>
                        <div class="border rounded-md p-4 <?= $isOverdue ? 'border-red-300 bg-red-50' : '' ?>">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-1">
                                        <?= e($fu['type']) ?>
                                    </span>
                                    <span class="ml-2 text-sm font-bold text-gray-800">
                                        <?= date('M d, Y h:i A', $fuDateTime) ?>
                                        <?php if ($isOverdue): ?><span class="text-red-600 text-xs ml-1">(Overdue)</span><?php endif; ?>
                                    </span>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusColor ?>"><?= e($fu['status']) ?></span>
                            </div>
                            <p class="text-sm text-gray-700 mb-2"><?= e($fu['remarks'] ?: 'No remarks.') ?></p>
                            <div class="flex justify-between items-center text-xs text-gray-500 mt-3 pt-3 border-t">
                                <span>Assigned to: <strong><?= e($fu['assigned_name']) ?></strong></span>
                                <div class="flex space-x-3">
                                    <?php if ($fu['status'] === 'Pending'): ?>
                                        <form method="POST" action="/follow-ups/<?= $fu['id'] ?>/complete" class="inline">
                                            <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Mark Complete</button>
                                        </form>
                                        <a href="/follow-ups/<?= $fu['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                    <?php endif; ?>
                                    <form method="POST" action="/follow-ups/<?= $fu['id'] ?>/delete" class="inline" onsubmit="return confirm('Delete this follow-up?');">
                                        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
