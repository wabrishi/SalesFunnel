<?php

namespace App\Repositories;

use PDO;
use App\Services\Database;

class OpportunityRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT o.*,
                   CONCAT(l.first_name, ' ', l.last_name) as lead_name,
                   l.company as lead_company,
                   CONCAT(u.first_name, ' ', u.last_name) as assigned_name
            FROM opportunities o
            JOIN leads l ON o.lead_id = l.id
            JOIN users u ON o.assigned_to = u.id
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT o.*,
                   CONCAT(l.first_name, ' ', l.last_name) as lead_name,
                   CONCAT(u.first_name, ' ', u.last_name) as assigned_name
            FROM opportunities o
            JOIN leads l ON o.lead_id = l.id
            JOIN users u ON o.assigned_to = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO opportunities (lead_id, name, value, stage, expected_close_date, probability, assigned_to, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['lead_id'],
            $data['name'],
            $data['value'] ?? 0.00,
            $data['stage'] ?? 'Lead Generated',
            $data['expected_close_date'] ?: null,
            $data['probability'] ?? 10,
            $data['assigned_to'],
            $data['created_by']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStage(int $id, string $newStage, int $userId): bool
    {
        $oldOp = $this->findById($id);
        if (!$oldOp || $oldOp['stage'] === $newStage) return false;

        $stmt = $this->db->prepare("UPDATE opportunities SET stage = ? WHERE id = ?");
        $success = $stmt->execute([$newStage, $id]);

        if ($success) {
            $daysInStage = floor((time() - strtotime($oldOp['updated_at'])) / (60 * 60 * 24));
            $histStmt = $this->db->prepare("
                INSERT INTO opportunity_history (opportunity_id, previous_stage, new_stage, changed_by, duration_in_stage_days)
                VALUES (?, ?, ?, ?, ?)
            ");
            $histStmt->execute([$id, $oldOp['stage'], $newStage, $userId, $daysInStage]);
        }

        return $success;
    }
}
