<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
    <div></div>
    <div class="flex items-center space-x-4">
        <?php if (\App\Helpers\Session::has('user_id')): ?>
            <span class="text-sm font-medium">Welcome, <?= e(\App\Helpers\Session::get('user_name', 'User')) ?></span>
            <form action="/logout" method="POST" class="inline">
                <?= \App\Middleware\CsrfMiddleware::csrfField() ?>
                <button type="submit" class="text-sm text-red-600">Logout</button>
            </form>
        <?php else: ?>
            <a href="/login" class="text-sm text-indigo-600">Login</a>
        <?php endif; ?>
    </div>
</header>
