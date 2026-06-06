<?php

namespace App\Services;

use PDO;

class RoleService
{
    private PDO $db;

    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->auditLogService = new AuditLogService();
    }

    // Previous RBAC logic
    public function getUserRoles(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public function getUserPermissions(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT DISTINCT p.name FROM permissions p JOIN role_permissions rp ON p.id = rp.permission_id JOIN user_roles ur ON rp.role_id = ur.role_id WHERE ur.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    public function hasRole(int $userId, string $role): bool { return in_array($role, $this->getUserRoles($userId)); }
    public function hasPermission(int $userId, string $permission): bool { return in_array($permission, $this->getUserPermissions($userId)); }

    // Role Management logic
    public function getAllRoles(): array
    {
        return $this->db->query("SELECT * FROM roles ORDER BY name")->fetchAll();
    }

    public function getRoleById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function createRole(array $data, array $permissionIds): bool
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
            $stmt->execute([$data['name'], $data['description']]);
            $roleId = $this->db->lastInsertId();

            $this->syncPermissions($roleId, $permissionIds);

            $this->auditLogService->log('Created Role', 'Role', $roleId, null, ['name' => $data['name'], 'permissions' => $permissionIds]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateRole(int $id, array $data, array $permissionIds): bool
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("UPDATE roles SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$data['name'], $data['description'], $id]);

            $this->syncPermissions($id, $permissionIds);

            $this->auditLogService->log('Updated Role', 'Role', $id, null, ['name' => $data['name'], 'permissions' => $permissionIds]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteRole(int $id): bool
    {
        return $this->db->prepare("DELETE FROM roles WHERE id = ?")->execute([$id]);
    }

    // Permission Management logic
    public function getAllPermissions(): array
    {
        return $this->db->query("SELECT * FROM permissions ORDER BY name")->fetchAll();
    }

    public function getRolePermissions(int $roleId): array
    {
        $stmt = $this->db->prepare("SELECT permission_id FROM role_permissions WHERE role_id = ?");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    private function syncPermissions(int $roleId, array $permissionIds): void
    {
        $this->db->prepare("DELETE FROM role_permissions WHERE role_id = ?")->execute([$roleId]);
        $stmt = $this->db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
        foreach ($permissionIds as $permId) {
            $stmt->execute([$roleId, (int)$permId]);
        }
    }
}
