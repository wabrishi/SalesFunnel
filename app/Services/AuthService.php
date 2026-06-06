<?php

namespace App\Services;

use PDO;
use App\Helpers\Session;

class AuthService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function attemptLogin(string $email, string $password): bool
    {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, password, status FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                Session::flash('error', 'Your account is disabled.');
                return false;
            }

            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
            return true;
        }

        Session::flash('error', 'Invalid email or password.');
        return false;
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function generatePasswordResetToken(string $email): ?string
    {
        // Verify user exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND status = 'active'");
        $stmt->execute([$email]);
        if (!$stmt->fetch()) return null;

        $token = bin2hex(random_bytes(32));

        // Remove old tokens
        $this->db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

        $this->db->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)")->execute([$email, password_hash($token, PASSWORD_DEFAULT)]);

        return $token;
    }

    public function validateResetToken(string $email, string $token): bool
    {
        $stmt = $this->db->prepare("SELECT token, created_at FROM password_resets WHERE email = ?");
        $stmt->execute([$email]);
        $record = $stmt->fetch();

        if (!$record) return false;

        // Check if expired (e.g., 1 hour)
        $createdAt = strtotime($record['created_at']);
        if (time() - $createdAt > 3600) {
            return false;
        }

        return password_verify($token, $record['token']);
    }

    public function resetPassword(string $email, string $token, string $newPassword): bool
    {
        if (!$this->validateResetToken($email, $token)) return false;

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->db->beginTransaction();
        try {
            $this->db->prepare("UPDATE users SET password = ? WHERE email = ?")->execute([$hashedPassword, $email]);
            $this->db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
