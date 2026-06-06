<?php

use PDO;

class SeedPermissionsPhase2
{
    public function up(PDO $db): void
    {
        // Missing lead permissions for Sales Managers/Executives
        $perms = ['create_leads', 'edit_leads', 'delete_leads', 'assign_leads'];

        // Get Admin Role ID
        $stmt = $db->query("SELECT id FROM roles WHERE name = 'Admin'");
        $adminRoleId = $stmt->fetchColumn();

        foreach ($perms as $perm) {
            // Check if permission exists first
            $check = $db->prepare("SELECT id FROM permissions WHERE name = ?");
            $check->execute([$perm]);
            $permId = $check->fetchColumn();

            if (!$permId) {
                $stmt = $db->prepare("INSERT INTO permissions (name) VALUES (?)");
                $stmt->execute([$perm]);
                $permId = $db->lastInsertId();
            }

            if ($adminRoleId) {
                $db->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)")->execute([$adminRoleId, $permId]);
            }
        }
    }

    public function down(PDO $db): void
    {
        // We won't remove permissions on rollback as they might be tied to other roles
    }
}
