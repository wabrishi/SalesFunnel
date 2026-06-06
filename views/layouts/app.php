<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'CRM') ?></title>
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased h-screen flex overflow-hidden">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <div class="flex-1 flex flex-col overflow-hidden">
        <?php require __DIR__ . '/../partials/header.php'; ?>
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <?php if ($err = \App\Helpers\Session::getFlash('error')): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= e($err) ?></div>
            <?php endif; ?>
            <?php if ($suc = \App\Helpers\Session::getFlash('success')): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= e($suc) ?></div>
            <?php endif; ?>
            <?php if (isset($contentView)) require dirname(__DIR__) . '/' . str_replace('.', '/', $contentView) . '.php'; ?>
        </main>
    </div>
</body>
</html>
