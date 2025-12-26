<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log($action, $module, $recordId = null, $details = null)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'details' => is_array($details) ? json_encode($details) : $details,
            'ip_address' => Request::ip()
        ]);
    }
}
