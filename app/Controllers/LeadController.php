<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Services\LeadService;
use App\Services\UserService;

class LeadController
{
    private LeadService $leadService;
    private UserService $userService;

    public function __construct()
    {
        $this->leadService = new LeadService();
        $this->userService = new UserService();
    }

    public function index(): void
    {
        $userId = Session::get('user_id');
        $roleService = new \App\Services\RoleService();
        $isManagerOrAdmin = $roleService->hasRole($userId, 'Admin') || $roleService->hasRole($userId, 'Sales Manager');

        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'priority' => $_GET['priority'] ?? '',
            'assigned_to' => $_GET['assigned_to'] ?? '',
            'restrict_to_user_id' => $isManagerOrAdmin ? null : $userId
        ];

        $leads = $this->leadService->getAllLeads($filters);
        $users = $this->userService->getAllUsers(); // For assignment filter

        View::render('layouts.app', [
            'title' => 'Leads Management',
            'contentView' => 'leads.index',
            'leads' => $leads,
            'users' => $users,
            'filters' => $filters
        ]);
    }

    public function create(): void
    {
        $users = $this->userService->getAllUsers();
        View::render('layouts.app', [
            'title' => 'Create Lead',
            'contentView' => 'leads.create',
            'users' => $users
        ]);
    }

    public function store(): void
    {
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'company' => trim($_POST['company'] ?? ''),
            'source' => trim($_POST['source'] ?? ''),
            'status' => $_POST['status'] ?? 'New',
            'priority' => $_POST['priority'] ?? 'Medium',
            'assigned_to' => $_POST['assigned_to'] ?: null
        ];

        if (empty($data['first_name']) || empty($data['last_name'])) {
            Session::flash('error', 'First name and last name are required.');
            Redirect::back();
        }

        $leadId = $this->leadService->createLead($data);

        if ($leadId) {
            Session::flash('success', 'Lead created successfully.');
            Redirect::to('/leads');
        } else {
            Redirect::back();
        }
    }

    private function canAccessLead(array $lead): bool
    {
        $userId = Session::get('user_id');
        $roleService = new \App\Services\RoleService();
        if ($roleService->hasRole($userId, 'Admin') || $roleService->hasRole($userId, 'Sales Manager')) {
            return true;
        }
        return $lead['assigned_to'] == $userId || $lead['created_by'] == $userId;
    }

    public function show(int $id): void
    {
        $lead = $this->leadService->getLeadById($id);
        if (!$lead || !$this->canAccessLead($lead)) {
            Session::flash('error', 'Lead not found or access denied.');
            Redirect::to('/leads');
        }

        // We need next/last follow up for the Health Score calculation
        $leadRepo = new \App\Repositories\LeadRepository();
        $enrichedLead = $leadRepo->findById($id);

        $users = $this->userService->getAllUsers();
        $followUps = (new \App\Services\FollowUpService())->getFollowUpsForLead($id);

        View::render('layouts.app', [
            'title' => 'Lead Details: ' . e($enrichedLead['first_name'] . ' ' . $enrichedLead['last_name']),
            'contentView' => 'leads.show',
            'lead' => $enrichedLead,
            'users' => $users,
            'followUps' => $followUps
        ]);
    }

    public function edit(int $id): void
    {
        $lead = $this->leadService->getLeadById($id);
        if (!$lead || !$this->canAccessLead($lead)) {
            Session::flash('error', 'Lead not found or access denied.');
            Redirect::to('/leads');
        }

        $users = $this->userService->getAllUsers();
        View::render('layouts.app', [
            'title' => 'Edit Lead',
            'contentView' => 'leads.edit',
            'lead' => $lead,
            'users' => $users
        ]);
    }

    public function update(int $id): void
    {
        $existingLead = $this->leadService->getLeadById($id);
        if (!$existingLead || !$this->canAccessLead($existingLead)) {
            Session::flash('error', 'Lead not found or access denied.');
            Redirect::to('/leads');
        }

        $userId = Session::get('user_id');
        $roleService = new \App\Services\RoleService();
        $canAssign = $roleService->hasRole($userId, 'Admin') || $roleService->hasRole($userId, 'Sales Manager');

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'company' => trim($_POST['company'] ?? ''),
            'source' => trim($_POST['source'] ?? ''),
            'status' => $_POST['status'] ?? 'New',
            'priority' => $_POST['priority'] ?? 'Medium',
        ];

        if ($canAssign) {
            $data['assigned_to'] = $_POST['assigned_to'] ?: null;
        } else {
            $data['assigned_to'] = $existingLead['assigned_to'];
        }

        if (empty($data['first_name']) || empty($data['last_name'])) {
            Session::flash('error', 'First name and last name are required.');
            Redirect::back();
        }

        if ($this->leadService->updateLead($id, $data)) {
            Session::flash('success', 'Lead updated successfully.');
            Redirect::to('/leads');
        } else {
            Session::flash('error', 'Failed to update lead.');
            Redirect::back();
        }
    }

    public function delete(int $id): void
    {
        $existingLead = $this->leadService->getLeadById($id);
        if (!$existingLead || !$this->canAccessLead($existingLead)) {
            Session::flash('error', 'Lead not found or access denied.');
            Redirect::to('/leads');
        }

        if ($this->leadService->deleteLead($id)) {
            Session::flash('success', 'Lead deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete lead.');
        }
        Redirect::to('/leads');
    }

    public function bulkAssign(): void
    {
        $userId = Session::get('user_id');
        $roleService = new \App\Services\RoleService();
        $canAssign = $roleService->hasRole($userId, 'Admin') || $roleService->hasRole($userId, 'Sales Manager');

        if (!$canAssign) {
            Session::flash('error', 'You do not have permission to bulk assign leads.');
            Redirect::to('/leads');
        }

        $leadIds = $_POST['lead_ids'] ?? [];
        $assignedTo = $_POST['assigned_to'] ?: null;

        if (empty($leadIds)) {
            Session::flash('error', 'No leads selected.');
            Redirect::back();
        }

        if ($this->leadService->bulkAssignLeads($leadIds, $assignedTo)) {
            Session::flash('success', 'Leads assigned successfully.');
        } else {
            Session::flash('error', 'Failed to assign leads.');
        }
        Redirect::to('/leads');
    }
}
