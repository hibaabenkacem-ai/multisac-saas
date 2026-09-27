<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetCompanyContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$user->company_id) {
            return response()->json([
                'message' => 'User is not attached to a company.',
            ], 403);
        }

        app()->instance('company_id', $user->company_id);

        DB::statement(
            "SELECT set_config('app.company_id', ?, false)",
            [(string) $user->company_id]
        );

        return $next($request);
    }
}