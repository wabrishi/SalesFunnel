<?php

namespace App\Services;

use App\Repositories\OpportunityRepository;
use App\Repositories\LeadRepository;
use App\Helpers\Session;

class OpportunityService
{
    private OpportunityRepository $opportunityRepository;
    private LeadRepository $leadRepository;
    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->opportunityRepository = new OpportunityRepository();
        $this->leadRepository = new LeadRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function getAllOpportunities(): array
    {
        return $this->opportunityRepository->getAll();
    }

    public function convertLeadToOpportunity(int $leadId, array $data): ?int
    {
        $lead = $this->leadRepository->findById($leadId);
        if (!$lead) return null;

        $data['lead_id'] = $leadId;
        $data['assigned_to'] = $data['assigned_to'] ?? $lead['assigned_to'] ?? Session::get('user_id');
        $data['created_by'] = Session::get('user_id');

        $opId = $this->opportunityRepository->create($data);

        if ($opId) {
            // Update Lead Status to 'Converted'
            $this->leadRepository->update($leadId, array_merge($lead, ['status' => 'Converted']));

            $this->auditLogService->log('Converted Lead to Opportunity', 'Lead', $leadId, ['status' => $lead['status']], ['status' => 'Converted', 'opportunity_id' => $opId]);
            $this->auditLogService->log('Created Opportunity', 'Opportunity', $opId, null, $data);
            return $opId;
        }

        return null;
    }

    public function updateOpportunityStage(int $id, string $newStage): bool
    {
        $userId = Session::get('user_id');
        $oldOp = $this->opportunityRepository->findById($id);

        if ($this->opportunityRepository->updateStage($id, $newStage, $userId)) {
            $this->auditLogService->log('Updated Opportunity Stage', 'Opportunity', $id, ['stage' => $oldOp['stage']], ['stage' => $newStage]);
            return true;
        }
        return false;
    }
}
