<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;
    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUserWithRoles(array $data, array $roleIds): bool
    {
        $db = $this->userRepository->getDb();
        try {
            $db->beginTransaction();
            $userId = $this->userRepository->create($data);
            foreach ($roleIds as $roleId) {
                $this->userRepository->assignRole($userId, (int)$roleId);
            }
            $this->auditLogService->log('Created User', 'User', $userId, null, ['email' => $data['email']]);
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
