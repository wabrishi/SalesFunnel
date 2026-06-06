<?php

namespace App\Services;

use PDO;
use App\Helpers\Session;

class AuditLogService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function log(string $action, string $entityType, int $entityId, ?array $oldValues = null, ?array $newValues = null): void
    {
        $userId = Session::get('user_id');
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;

        $stmt = $this->db->prepare("
            INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $userId,
            $action,
            $entityType,
            $entityId,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $ipAddress
        ]);
    }

    public function getLogs(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.first_name, u.last_name, u.email
            FROM audit_logs a
            LEFT JOIN users u ON a.user_id = u.id
            ORDER BY a.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
