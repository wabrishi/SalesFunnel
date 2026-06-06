<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Services\Database;

class HomeController
{
    public function index(): void
    {
        $db = Database::getInstance();

        // New Leads Indicator
        $stmt = $db->query("SELECT COUNT(*) FROM leads WHERE status = 'New'");
        $newLeads = $stmt->fetchColumn() ?: 0;

        // Unassigned Leads Indicator
        $stmt = $db->query("SELECT COUNT(*) FROM leads WHERE assigned_to IS NULL");
        $unassignedLeads = $stmt->fetchColumn() ?: 0;

        // Qualified Leads
        $stmt = $db->query("SELECT COUNT(*) FROM leads WHERE status = 'Qualified'");
        $qualifiedLeads = $stmt->fetchColumn() ?: 0;

        // Total Leads
        $stmt = $db->query("SELECT COUNT(*) FROM leads");
        $totalLeads = $stmt->fetchColumn() ?: 0;

        // Follow-Up Metrics
        $today = date('Y-m-d');

        $stmt = $db->prepare("SELECT COUNT(*) FROM follow_ups WHERE status = 'Pending' AND follow_up_date = ?");
        $stmt->execute([$today]);
        $dueToday = $stmt->fetchColumn() ?: 0;

        $stmt = $db->prepare("SELECT COUNT(*) FROM follow_ups WHERE status = 'Pending' AND CONCAT(follow_up_date, ' ', follow_up_time) < NOW()");
        $stmt->execute();
        $overdue = $stmt->fetchColumn() ?: 0;

        $stmt = $db->prepare("SELECT COUNT(*) FROM follow_ups WHERE status = 'Missed'");
        $stmt->execute();
        $missed = $stmt->fetchColumn() ?: 0;

        $stmt = $db->prepare("SELECT COUNT(*) FROM follow_ups WHERE status = 'Completed' AND DATE(updated_at) = ?");
        $stmt->execute([$today]);
        $completedToday = $stmt->fetchColumn() ?: 0;

        View::render('layouts.app', [
            'title' => 'Dashboard - Sales Funnel CRM',
            'contentView' => 'home',
            'metrics' => [
                'newLeads' => $newLeads,
                'unassignedLeads' => $unassignedLeads,
                'qualifiedLeads' => $qualifiedLeads,
                'totalLeads' => $totalLeads,
                'dueToday' => $dueToday,
                'overdue' => $overdue,
                'missed' => $missed,
                'completedToday' => $completedToday
            ]
        ]);
    }
}
