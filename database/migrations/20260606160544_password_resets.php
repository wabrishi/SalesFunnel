<?php

use PDO;

class PasswordResets
{
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS password_resets (
                email VARCHAR(100) NOT NULL,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (email)
            );
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS password_resets;");
    }
}
