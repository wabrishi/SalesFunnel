<?php

namespace App\Services;

use App\Repositories\LeadRepository;
use App\Helpers\Session;

class LeadService
{
    private LeadRepository $leadRepository;
    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->leadRepository = new LeadRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function getAllLeads(array $filters = []): array
    {
        return $this->leadRepository->getAll($filters);
    }

    public function getLeadById(int $id): ?array
    {
        return $this->leadRepository->findById($id);
    }

    public function createLead(array $data): ?int
    {
        if ($this->leadRepository->checkDuplicate($data['email'] ?? null, $data['phone'] ?? null)) {
            Session::flash('error', 'A lead with this email or phone already exists.');
            return null;
        }

        $data['created_by'] = Session::get('user_id');
        $leadId = $this->leadRepository->create($data);

        if ($leadId) {
            $this->auditLogService->log('Created Lead', 'Lead', $leadId, null, $data);
            return $leadId;
        }

        return null;
    }

    public function updateLead(int $id, array $data): bool
    {
        $oldLead = $this->leadRepository->findById($id);
        if (!$oldLead) {
            return false;
        }

        // Check for duplicate excluding the current lead
        if ($this->leadRepository->checkDuplicate($data['email'] ?? null, $data['phone'] ?? null, $id)) {
            Session::flash('error', 'A lead with this email or phone already exists.');
            return false;
        }

        $success = $this->leadRepository->update($id, $data);

        if ($success) {
            $this->auditLogService->log('Updated Lead', 'Lead', $id, $oldLead, $data);

            // Log Assignment Change explicitly
            if ($oldLead['assigned_to'] != $data['assigned_to']) {
                $this->auditLogService->log('Reassigned Lead', 'Lead', $id,
                    ['assigned_to' => $oldLead['assigned_to']],
                    ['assigned_to' => $data['assigned_to']]
                );
            }
        }

        return $success;
    }

    public function deleteLead(int $id): bool
    {
        $lead = $this->leadRepository->findById($id);
        if ($lead) {
            $success = $this->leadRepository->delete($id);
            if ($success) {
                $this->auditLogService->log('Deleted Lead', 'Lead', $id, $lead, null);
            }
            return $success;
        }
        return false;
    }
}
