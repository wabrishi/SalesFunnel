<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Services\OpportunityService;
use App\Services\UserService;

class OpportunityController
{
    private OpportunityService $opportunityService;

    public function __construct()
    {
        $this->opportunityService = new OpportunityService();
    }

    public function index(): void
    {
        $opportunities = $this->opportunityService->getAllOpportunities();

        View::render('layouts.app', [
            'title' => 'Sales Funnel Kanban',
            'contentView' => 'opportunities.kanban',
            'opportunities' => $opportunities
        ]);
    }

    public function convertLead(int $leadId): void
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'value' => $_POST['value'] ?? 0,
            'expected_close_date' => $_POST['expected_close_date'] ?? null,
            'probability' => $_POST['probability'] ?? 10
        ];

        if (empty($data['name'])) {
            Session::flash('error', 'Opportunity Name is required.');
            Redirect::back();
        }

        $opId = $this->opportunityService->convertLeadToOpportunity($leadId, $data);

        if ($opId) {
            Session::flash('success', 'Lead successfully converted to Opportunity.');
            Redirect::to('/opportunities');
        } else {
            Session::flash('error', 'Failed to convert Lead.');
            Redirect::back();
        }
    }

    public function updateStage(int $id): void
    {
        // For Kanban drag and drop / API request
        $newStage = $_POST['stage'] ?? '';

        if ($this->opportunityService->updateOpportunityStage($id, $newStage)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }
    }
}
