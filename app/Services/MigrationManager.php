<?php

namespace App\Services;

use PDO;

class MigrationManager
{
    private PDO $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->db->exec("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), batch INT)");
    }
    public function migrate(): void
    {
        $files = glob(dirname(__DIR__, 2) . '/database/migrations/*.php');
        if (!$files) return;
        sort($files);
        $executed = $this->db->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);
        $batch = ($this->db->query("SELECT MAX(batch) FROM migrations")->fetchColumn() ?: 0) + 1;

        foreach ($files as $file) {
            $name = basename($file);
            if (!in_array($name, $executed)) {
                require_once $file;
                $class = implode('', array_map('ucfirst', explode('_', preg_replace('/^\d+_|\.php$/', '', $name))));
                if (class_exists($class)) {
                    (new $class())->up($this->db);
                    $this->db->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)")->execute([$name, $batch]);
                    echo "Migrated: $name\n";
                }
            }
        }
    }
}
