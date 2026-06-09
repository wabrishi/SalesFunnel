<?php

use PDO;

class CustomersSchema
{
    public function up(PDO $db): void
    {
        $db->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                company_name VARCHAR(150) NULL,
                email VARCHAR(100) NULL,
                phone VARCHAR(20) NULL,
                gst_number VARCHAR(50) NULL,
                industry VARCHAR(100) NULL,
                assigned_to INT NULL,
                created_by INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS customer_contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer_id INT NOT NULL,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                email VARCHAR(100) NULL,
                phone VARCHAR(20) NULL,
                designation VARCHAR(100) NULL,
                is_primary BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS customer_addresses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer_id INT NOT NULL,
                address_type ENUM('Billing', 'Shipping', 'Office') DEFAULT 'Billing',
                street_address TEXT NOT NULL,
                city VARCHAR(100) NOT NULL,
                state VARCHAR(100) NOT NULL,
                postal_code VARCHAR(20) NOT NULL,
                country VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
            );

            -- Add customer_id to opportunities to link won opportunities to customers
            ALTER TABLE opportunities ADD COLUMN customer_id INT NULL AFTER lead_id;
            ALTER TABLE opportunities ADD CONSTRAINT fk_opportunities_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL;
        ");
    }

    public function down(PDO $db): void
    {
        $db->exec("ALTER TABLE opportunities DROP FOREIGN KEY fk_opportunities_customer");
        $db->exec("ALTER TABLE opportunities DROP COLUMN customer_id");
        $db->exec("DROP TABLE IF EXISTS customer_addresses");
        $db->exec("DROP TABLE IF EXISTS customer_contacts");
        $db->exec("DROP TABLE IF EXISTS customers");
    }
}
