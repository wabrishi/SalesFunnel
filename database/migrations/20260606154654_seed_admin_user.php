<?php

use PDO;

class SeedAdminUser
{
    public function up(PDO $db): void
    {
        $db->exec("INSERT INTO roles (name, description) VALUES ('Admin', 'System Administrator'), ('Sales Manager', 'Manager'), ('Sales Executive', 'Rep')");
        $adminRoleId = $db->lastInsertId();

        $pw = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES ('Super', 'Admin', 'admin@example.com', ?)");
        $stmt->execute([$pw]);
        $adminUserId = $db->lastInsertId();

        $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)")->execute([$adminUserId, $adminRoleId]);

        $perms = ['manage_users', 'manage_roles', 'manage_leads', 'view_leads', 'manage_opportunities'];
        foreach ($perms as $perm) {
            $stmt = $db->prepare("INSERT INTO permissions (name) VALUES (?)");
            $stmt->execute([$perm]);
            $permId = $db->lastInsertId();
            $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)")->execute([$adminRoleId, $permId]);
        }
    }
}
