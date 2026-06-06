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
        $filters = [
            'search' => $_GET['search'] ?? '',
            'status' => $_GET['status'] ?? '',
            'priority' => $_GET['priority'] ?? '',
            'assigned_to' => $_GET['assigned_to'] ?? ''
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

    public function edit(int $id): void
    {
        $lead = $this->leadService->getLeadById($id);
        if (!$lead) {
            Session::flash('error', 'Lead not found.');
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
        if ($this->leadService->deleteLead($id)) {
            Session::flash('success', 'Lead deleted successfully.');
        } else {
            Session::flash('error', 'Failed to delete lead.');
        }
        Redirect::to('/leads');
    }
}
