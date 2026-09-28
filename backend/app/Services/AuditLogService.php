<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogService
{
    public function log(
        Request $request,
        string $action,
        ?string $entityType = null,
        ?int $entityId = null
    ): AuditLog {
        $user = $request->user();

        return AuditLog::create([
            'company_id' => $user?->company_id,
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}