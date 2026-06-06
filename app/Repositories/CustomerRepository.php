<?php

namespace App\Repositories;

use PDO;
use App\Services\Database;

class CustomerRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT c.*,
                   CONCAT(u.first_name, ' ', u.last_name) as assigned_name
            FROM customers c
            LEFT JOIN users u ON c.assigned_to = u.id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT c.*,
                   CONCAT(u.first_name, ' ', u.last_name) as assigned_name
            FROM customers c
            LEFT JOIN users u ON c.assigned_to = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO customers (name, company_name, email, phone, gst_number, industry, assigned_to, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'],
            $data['company_name'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['gst_number'] ?? null,
            $data['industry'] ?? null,
            $data['assigned_to'] ?: null,
            $data['created_by']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE customers
            SET name = ?, company_name = ?, email = ?, phone = ?, gst_number = ?, industry = ?, assigned_to = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['company_name'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['gst_number'] ?? null,
            $data['industry'] ?? null,
            $data['assigned_to'] ?: null,
            $id
        ]);
    }

    // Contacts
    public function getContacts(int $customerId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM customer_contacts WHERE customer_id = ? ORDER BY is_primary DESC, created_at ASC");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    public function addContact(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO customer_contacts (customer_id, first_name, last_name, email, phone, designation, is_primary)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['customer_id'],
            $data['first_name'],
            $data['last_name'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['designation'] ?? null,
            $data['is_primary'] ?? 0
        ]);
    }

    // Associated Entities
    public function getOpportunities(int $customerId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM opportunities WHERE customer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }
}
