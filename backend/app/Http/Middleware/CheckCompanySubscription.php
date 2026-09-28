<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanySubscription
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$user->company_id) {
            return $next($request);
        }

        $company = $user->company;

        if (!$company) {
            return response()->json([
                'message' => 'Company not found.',
            ], 403);
        }

        if ($company->status !== 'active') {
            return response()->json([
                'message' => 'Company is disabled.',
            ], 403);
        }

        if ($company->subscription_status === 'terminated') {
            return response()->json([
                'message' => 'Company subscription is terminated.',
            ], 403);
        }

        if ($company->subscription_status === 'suspended') {
            return response()->json([
                'message' => 'Company subscription is suspended.',
            ], 403);
        }

        return $next($request);
    }
}