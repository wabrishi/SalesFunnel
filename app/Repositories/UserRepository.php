<?php

namespace App\Repositories;

use PDO;
use App\Services\Database;

class UserRepository
{
    private PDO $db;

    public function __construct() { $this->db = Database::getInstance(); }
    public function getDb(): PDO { return $this->db; }

    public function getAllUsers(): array
    {
        return $this->db->query("
            SELECT u.*, GROUP_CONCAT(r.name SEPARATOR ', ') as roles
            FROM users u LEFT JOIN user_roles ur ON u.id = ur.user_id LEFT JOIN roles r ON ur.role_id = r.id
            GROUP BY u.id ORDER BY u.created_at DESC
        ")->fetchAll();
    }

    public function create(array $data): int
    {
        $this->db->prepare("INSERT INTO users (first_name, last_name, email, password, status) VALUES (?, ?, ?, ?, ?)")->execute([
            $data['first_name'], $data['last_name'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT), $data['status'] ?? 'active'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, status = ?";
        $params = [$data['first_name'], $data['last_name'], $data['email'], $data['status']];

        if (!empty($data['password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function removeAllRoles(int $userId): void
    {
        $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?")->execute([$userId]);
    }

    public function assignRole(int $userId, int $roleId): void
    {
        $this->db->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)")->execute([$userId, $roleId]);
    }
}
