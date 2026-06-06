<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Reset Password') ?></title>
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex h-screen items-center justify-center">
<div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Create New Password</h2>

    <?php if ($error = \App\Helpers\Session::getFlash('error')): ?>
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/reset-password">
        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
        <input type="hidden" name="email" value="<?= e($email) ?>">
        <input type="hidden" name="token" value="<?= e($token) ?>">

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="password" required minlength="8" class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" required minlength="8" class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Reset Password</button>
    </form>
</div>
</body>
</html>