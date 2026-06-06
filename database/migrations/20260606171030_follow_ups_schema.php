<?php

use PDO;

class FollowUpsSchema
{
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS follow_ups (
                id INT AUTO_INCREMENT PRIMARY KEY,
                lead_id INT NOT NULL,
                type ENUM('Call', 'Meeting', 'WhatsApp', 'Email', 'Site Visit', 'Demo', 'Proposal Discussion') NOT NULL,
                follow_up_date DATE NOT NULL,
                follow_up_time TIME NOT NULL,
                remarks TEXT NULL,
                assigned_to INT NOT NULL,
                status ENUM('Pending', 'Completed', 'Missed', 'Cancelled') DEFAULT 'Pending',
                created_by INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
                FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
            );
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS follow_ups");
    }
}
