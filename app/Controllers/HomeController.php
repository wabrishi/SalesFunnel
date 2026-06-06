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

        View::render('layouts.app', [
            'title' => 'Dashboard - Sales Funnel CRM',
            'contentView' => 'home',
            'metrics' => [
                'newLeads' => $newLeads,
                'unassignedLeads' => $unassignedLeads,
                'qualifiedLeads' => $qualifiedLeads,
                'totalLeads' => $totalLeads,
            ]
        ]);
    }
}
