<?php

use PDO;

class OpportunitiesSchema
{
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS opportunities (
                id INT AUTO_INCREMENT PRIMARY KEY,
                lead_id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                value DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
                stage ENUM('Lead Generated', 'Qualification', 'Requirement Gathering', 'Proposal Shared', 'Negotiation', 'Decision Pending', 'Won', 'Lost') NOT NULL DEFAULT 'Lead Generated',
                expected_close_date DATE NULL,
                probability INT NOT NULL DEFAULT 10,
                assigned_to INT NOT NULL,
                created_by INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
                FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS opportunity_history (
                id INT AUTO_INCREMENT PRIMARY KEY,
                opportunity_id INT NOT NULL,
                previous_stage ENUM('Lead Generated', 'Qualification', 'Requirement Gathering', 'Proposal Shared', 'Negotiation', 'Decision Pending', 'Won', 'Lost') NULL,
                new_stage ENUM('Lead Generated', 'Qualification', 'Requirement Gathering', 'Proposal Shared', 'Negotiation', 'Decision Pending', 'Won', 'Lost') NOT NULL,
                changed_by INT NOT NULL,
                duration_in_stage_days INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE CASCADE,
                FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE CASCADE
            );
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("DROP TABLE IF EXISTS opportunity_history");
        $db->exec("DROP TABLE IF EXISTS opportunities");
    }
}
