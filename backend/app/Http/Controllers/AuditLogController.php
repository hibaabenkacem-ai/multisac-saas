<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with([
            'user',
            'company',
        ])
        ->orderByDesc('id');

        $currentUser = $request->user();

        $isEditor = $currentUser->roles()
            ->where('slug', 'editor')
            ->exists();

        if (!$isEditor) {
            $query->where(
                'company_id',
                $currentUser->company_id
            );
        }

        return response()->json([
            'audit_logs' => $query->paginate(50),
        ]);
    }

    public function show(
        Request $request,
        AuditLog $auditLog
    ) {
        $currentUser = $request->user();

        $isEditor = $currentUser->roles()
            ->where('slug', 'editor')
            ->exists();

        if (
            !$isEditor &&
            $auditLog->company_id !== $currentUser->company_id
        ) {
            return response()->json([
                'message' => 'Access denied.',
            ], 403);
        }

        return response()->json([
            'audit_log' => $auditLog->load([
                'user',
                'company',
            ]),
        ]);
    }
}