<?php

namespace App\Services;

use App\Repositories\FollowUpRepository;
use App\Helpers\Session;

class FollowUpService
{
    private FollowUpRepository $followUpRepository;
    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->followUpRepository = new FollowUpRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function getFollowUpsForLead(int $leadId): array
    {
        return $this->followUpRepository->getByLeadId($leadId);
    }

    public function getFilteredFollowUps(string $filter = ''): array
    {
        return $this->followUpRepository->getFiltered($filter);
    }

    public function getFollowUpById(int $id): ?array
    {
        return $this->followUpRepository->findById($id);
    }

    public function createFollowUp(array $data): ?int
    {
        $data['created_by'] = Session::get('user_id');
        $id = $this->followUpRepository->create($data);

        if ($id) {
            $this->auditLogService->log('Created Follow-Up', 'FollowUp', $id, null, $data);
            return $id;
        }
        return null;
    }

    public function updateFollowUp(int $id, array $data): bool
    {
        $oldFollowUp = $this->followUpRepository->findById($id);
        if (!$oldFollowUp) return false;

        $success = $this->followUpRepository->update($id, $data);
        if ($success) {
            $this->auditLogService->log('Updated Follow-Up', 'FollowUp', $id, $oldFollowUp, $data);
        }
        return $success;
    }

    public function deleteFollowUp(int $id): bool
    {
        $oldFollowUp = $this->followUpRepository->findById($id);
        if (!$oldFollowUp) return false;

        $success = $this->followUpRepository->delete($id);
        if ($success) {
            $this->auditLogService->log('Deleted Follow-Up', 'FollowUp', $id, $oldFollowUp, null);
        }
        return $success;
    }

    public function changeStatus(int $id, string $status): bool
    {
        $oldFollowUp = $this->followUpRepository->findById($id);
        if (!$oldFollowUp) return false;

        $success = $this->followUpRepository->updateStatus($id, $status);
        if ($success) {
            $this->auditLogService->log("Marked Follow-Up $status", 'FollowUp', $id, ['status' => $oldFollowUp['status']], ['status' => $status]);
        }
        return $success;
    }
}
