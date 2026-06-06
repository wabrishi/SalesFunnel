<?php

namespace App\Repositories;

use PDO;
use App\Services\Database;

class FollowUpRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByLeadId(int $leadId): array
    {
        $stmt = $this->db->prepare("
            SELECT f.*,
                   CONCAT(u.first_name, ' ', u.last_name) as assigned_name,
                   CONCAT(c.first_name, ' ', c.last_name) as creator_name
            FROM follow_ups f
            JOIN users u ON f.assigned_to = u.id
            JOIN users c ON f.created_by = c.id
            WHERE f.lead_id = ?
            ORDER BY f.follow_up_date DESC, f.follow_up_time DESC
        ");
        $stmt->execute([$leadId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM follow_ups WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO follow_ups (lead_id, type, follow_up_date, follow_up_time, remarks, assigned_to, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['lead_id'],
            $data['type'],
            $data['follow_up_date'],
            $data['follow_up_time'],
            $data['remarks'],
            $data['assigned_to'],
            $data['status'] ?? 'Pending',
            $data['created_by']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE follow_ups
            SET type = ?, follow_up_date = ?, follow_up_time = ?, remarks = ?, assigned_to = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['type'],
            $data['follow_up_date'],
            $data['follow_up_time'],
            $data['remarks'],
            $data['assigned_to'],
            $data['status'],
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM follow_ups WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE follow_ups SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
