<?php

use PDO;

class SeedPermissionsPhase3
{
    public function up(PDO $db): void
    {
        $perms = ['manage_follow_ups'];

        $stmt = $db->query("SELECT id FROM roles WHERE name = 'Admin'");
        $adminRoleId = $stmt->fetchColumn();

        foreach ($perms as $perm) {
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
    }
}
