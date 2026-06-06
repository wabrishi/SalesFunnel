<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Forgot Password') ?></title>
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex h-screen items-center justify-center">
<div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Reset Password</h2>

    <?php if ($error = \App\Helpers\Session::getFlash('error')): ?>
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4"><?= e($error) ?></div>
    <?php endif; ?>
    <?php if ($success = \App\Helpers\Session::getFlash('success')): ?>
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4"><?= $success // allow html for simulation link ?></div>
    <?php endif; ?>

    <form method="POST" action="/forgot-password">
        <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="mb-4 text-sm text-right">
            <a href="/login" class="text-indigo-600 hover:text-indigo-800">Back to Login</a>
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Send Reset Link</button>
    </form>
</div>
</body>
</html>