<?php
$stages = ['Lead Generated', 'Qualification', 'Requirement Gathering', 'Proposal Shared', 'Negotiation', 'Decision Pending', 'Won', 'Lost'];

// Group opportunities by stage
$kanban = array_fill_keys($stages, []);
$stageMetrics = array_fill_keys($stages, ['count' => 0, 'value' => 0]);

foreach ($opportunities as $op) {
    if (isset($kanban[$op['stage']])) {
        $kanban[$op['stage']][] = $op;
        $stageMetrics[$op['stage']]['count']++;
        $stageMetrics[$op['stage']]['value'] += $op['value'];
    }
}
?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Sales Funnel Kanban</h2>
</div>

<div class="flex overflow-x-auto pb-8 space-x-4 min-h-[calc(100vh-200px)]">
    <?php foreach ($stages as $stage): ?>
        <div class="w-80 flex-shrink-0 bg-gray-100 rounded-lg flex flex-col border border-gray-200" data-stage="<?= e($stage) ?>">
            <!-- Stage Header -->
            <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                <h3 class="font-bold text-gray-700"><?= e($stage) ?></h3>
                <div class="flex justify-between text-xs text-gray-500 mt-1 font-medium">
                    <span><?= $stageMetrics[$stage]['count'] ?> Deals</span>
                    <span>$<?= number_format($stageMetrics[$stage]['value'], 2) ?></span>
                </div>
            </div>

            <!-- Cards Container -->
            <div class="p-3 flex-1 overflow-y-auto space-y-3 kanban-column" id="col-<?= md5($stage) ?>">
                <?php foreach ($kanban[$stage] as $op): ?>
                    <div class="bg-white p-4 rounded shadow-sm border border-gray-200 cursor-move hover:shadow-md transition-shadow" draggable="true" data-id="<?= $op['id'] ?>">
                        <div class="font-bold text-indigo-600 text-sm mb-1"><?= e($op['name']) ?></div>
                        <div class="text-xs text-gray-500 mb-2">Lead: <?= e($op['lead_name']) ?></div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-gray-700">$<?= number_format($op['value'], 0) ?></span>
                            <span class="text-xs text-gray-400"><?= e($op['probability']) ?>%</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const columns = document.querySelectorAll('.kanban-column');
    let draggedItem = null;

    document.querySelectorAll('[draggable="true"]').forEach(item => {
        item.addEventListener('dragstart', (e) => {
            draggedItem = item;
            setTimeout(() => item.classList.add('opacity-50'), 0);
        });
        item.addEventListener('dragend', () => {
            setTimeout(() => {
                draggedItem.classList.remove('opacity-50');
                draggedItem = null;
            }, 0);
        });
    });

    columns.forEach(column => {
        column.addEventListener('dragover', e => {
            e.preventDefault();
            column.classList.add('bg-gray-200');
        });
        column.addEventListener('dragleave', () => {
            column.classList.remove('bg-gray-200');
        });
        column.addEventListener('drop', e => {
            e.preventDefault();
            column.classList.remove('bg-gray-200');
            if (draggedItem) {
                column.appendChild(draggedItem);
                const newStage = column.closest('[data-stage]').dataset.stage;
                const opId = draggedItem.dataset.id;

                // Perform Ajax Request
                const formData = new FormData();
                formData.append('stage', newStage);
                formData.append('_csrf', '<?= \App\Middleware\CsrfMiddleware::generateToken() ?>');

                fetch(`/opportunities/${opId}/stage`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        // Optionally refresh to update stage metrics accurately
                        window.location.reload();
                    } else {
                        alert('Failed to update stage');
                        window.location.reload();
                    }
                });
            }
        });
    });
});
</script>