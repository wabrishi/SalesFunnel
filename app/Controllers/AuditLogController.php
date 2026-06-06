<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Services\AuditLogService;

class AuditLogController
{
    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->auditLogService = new AuditLogService();
    }

    public function index(): void
    {
        $logs = $this->auditLogService->getLogs(100); // fetch last 100 logs

        View::render('layouts.app', [
            'title' => 'Audit Logs',
            'contentView' => 'audit_logs.index',
            'logs' => $logs
        ]);
    }
}
