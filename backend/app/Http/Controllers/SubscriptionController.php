<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function update(
        Request $request,
        Company $company,
        AuditLogService $auditLogService
    ) {
        $validated = $request->validate([
            'subscription_status' => [
                'required',
                Rule::in([
                    'active',
                    'trial',
                    'unpaid',
                    'restricted',
                    'suspended',
                    'terminated',
                ]),
            ],

            'subscription_started_at' => [
                'nullable',
                'date',
            ],

            'subscription_ends_at' => [
                'nullable',
                'date',
                'after_or_equal:subscription_started_at',
            ],
        ]);

        $company->update($validated);

        $auditLogService->log(
            $request,
            'subscription.updated',
            'Company',
            $company->id
        );

        return response()->json([
            'message' => 'Subscription updated successfully.',
            'company' => $company->fresh(),
        ]);
    }
}