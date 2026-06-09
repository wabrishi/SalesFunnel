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

    public function updateUserWithRoles(int $id, array $data, array $roleIds): bool
    {
        $db = $this->userRepository->getDb();
        try {
            $db->beginTransaction();
            $this->userRepository->update($id, $data);
            $this->userRepository->removeAllRoles($id);
            foreach ($roleIds as $roleId) {
                $this->userRepository->assignRole($id, (int)$roleId);
            }
            $this->auditLogService->log('Updated User', 'User', $id, null, ['email' => $data['email'], 'roles' => $roleIds]);
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
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
