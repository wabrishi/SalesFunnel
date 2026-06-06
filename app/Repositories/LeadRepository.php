<?php

namespace App\Repositories;

use PDO;
use App\Services\Database;

class LeadRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(array $filters = [], int $limit = 100, int $offset = 0): array
    {
        $sql = "
            SELECT l.*,
                   CONCAT(a.first_name, ' ', a.last_name) as assigned_name,
                   CONCAT(c.first_name, ' ', c.last_name) as creator_name
            FROM leads l
            LEFT JOIN users a ON l.assigned_to = a.id
            LEFT JOIN users c ON l.created_by = c.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND l.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $sql .= " AND l.priority = ?";
            $params[] = $filters['priority'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (l.first_name LIKE ? OR l.last_name LIKE ? OR l.email LIKE ? OR l.company LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        }

        if (!empty($filters['assigned_to'])) {
            $sql .= " AND l.assigned_to = ?";
            $params[] = $filters['assigned_to'];
        }

        $sql .= " ORDER BY l.created_at DESC LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT l.*,
                   CONCAT(a.first_name, ' ', a.last_name) as assigned_name,
                   CONCAT(c.first_name, ' ', c.last_name) as creator_name
            FROM leads l
            LEFT JOIN users a ON l.assigned_to = a.id
            LEFT JOIN users c ON l.created_by = c.id
            WHERE l.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO leads (first_name, last_name, email, phone, company, source, status, priority, assigned_to, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['company'] ?? null,
            $data['source'] ?? null,
            $data['status'] ?? 'New',
            $data['priority'] ?? 'Medium',
            $data['assigned_to'] ?: null,
            $data['created_by']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE leads
            SET first_name = ?, last_name = ?, email = ?, phone = ?, company = ?, source = ?, status = ?, priority = ?, assigned_to = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['company'] ?? null,
            $data['source'] ?? null,
            $data['status'],
            $data['priority'],
            $data['assigned_to'] ?: null,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM leads WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function checkDuplicate(?string $email, ?string $phone, ?int $excludeId = null): bool
    {
        if (empty($email) && empty($phone)) return false;

        $conditions = [];
        $params = [];

        if (!empty($email)) {
            $conditions[] = "email = ?";
            $params[] = $email;
        }

        if (!empty($phone)) {
            $conditions[] = "phone = ?";
            $params[] = $phone;
        }

        $sql = "SELECT id FROM leads WHERE (" . implode(" OR ", $conditions) . ")";

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }
}
