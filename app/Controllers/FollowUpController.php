<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Services\FollowUpService;
use App\Services\UserService;
use App\Services\LeadService;

class FollowUpController
{
    private FollowUpService $followUpService;

    public function __construct()
    {
        $this->followUpService = new FollowUpService();
    }

    public function index(): void
    {
        $filter = $_GET['filter'] ?? '';
        $followUps = $this->followUpService->getFilteredFollowUps($filter);

        View::render('layouts.app', [
            'title' => 'Global Follow-Ups',
            'contentView' => 'follow_ups.index',
            'followUps' => $followUps,
            'filter' => $filter
        ]);
    }

    public function store(int $leadId): void
    {
        $data = [
            'lead_id' => $leadId,
            'type' => $_POST['type'] ?? '',
            'follow_up_date' => $_POST['follow_up_date'] ?? '',
            'follow_up_time' => $_POST['follow_up_time'] ?? '',
            'remarks' => $_POST['remarks'] ?? '',
            'assigned_to' => $_POST['assigned_to'] ?? Session::get('user_id'),
            'status' => 'Pending'
        ];

        if (empty($data['type']) || empty($data['follow_up_date']) || empty($data['follow_up_time'])) {
            Session::flash('error', 'Type, Date, and Time are required.');
            Redirect::back();
        }

        if ($this->followUpService->createFollowUp($data)) {
            Session::flash('success', 'Follow-Up created successfully.');
        } else {
            Session::flash('error', 'Failed to create follow-up.');
        }

        Redirect::back();
    }

    public function edit(int $id): void
    {
        $followUp = $this->followUpService->getFollowUpById($id);
        if (!$followUp) {
            Session::flash('error', 'Follow-up not found.');
            Redirect::back();
        }

        $users = (new UserService())->getAllUsers();

        View::render('layouts.app', [
            'title' => 'Edit Follow-Up',
            'contentView' => 'follow_ups.edit',
            'followUp' => $followUp,
            'users' => $users
        ]);
    }

    public function update(int $id): void
    {
        $followUp = $this->followUpService->getFollowUpById($id);
        if (!$followUp) {
            Session::flash('error', 'Follow-up not found.');
            Redirect::back();
        }

        $data = [
            'type' => $_POST['type'] ?? '',
            'follow_up_date' => $_POST['follow_up_date'] ?? '',
            'follow_up_time' => $_POST['follow_up_time'] ?? '',
            'remarks' => $_POST['remarks'] ?? '',
            'assigned_to' => $_POST['assigned_to'] ?? $followUp['assigned_to'],
            'status' => $_POST['status'] ?? $followUp['status']
        ];

        if ($this->followUpService->updateFollowUp($id, $data)) {
            Session::flash('success', 'Follow-Up updated successfully.');
            Redirect::to("/leads/{$followUp['lead_id']}");
        } else {
            Session::flash('error', 'Failed to update follow-up.');
            Redirect::back();
        }
    }

    public function complete(int $id): void
    {
        if ($this->followUpService->changeStatus($id, 'Completed')) {
            Session::flash('success', 'Follow-Up marked as completed.');
        } else {
            Session::flash('error', 'Failed to complete follow-up.');
        }
        Redirect::back();
    }

    public function delete(int $id): void
    {
        if ($this->followUpService->deleteFollowUp($id)) {
            Session::flash('success', 'Follow-Up deleted.');
        } else {
            Session::flash('error', 'Failed to delete follow-up.');
        }
        Redirect::back();
    }
}
