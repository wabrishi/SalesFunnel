<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Helpers\Env;
use App\Services\MigrationManager;

Env::load(__DIR__ . '/.env');

$cmd = $argv[1] ?? '';
if ($cmd === 'make') {
    $name = $argv[2] ?? 'migration';
    $time = date('YmdHis');
    $file = "database/migrations/{$time}_{$name}.php";
    $class = implode('', array_map('ucfirst', explode('_', $name)));
    file_put_contents($file, "<?php\n\nuse PDO;\n\nclass $class { public function up(PDO \$db): void {} public function down(PDO \$db): void {} }");
    echo "Created: $file\n";
} elseif ($cmd === 'migrate') {
    (new MigrationManager())->migrate();
}
