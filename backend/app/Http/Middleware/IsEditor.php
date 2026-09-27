<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsEditor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $isEditor = $user->roles()
            ->where('slug', 'editor')
            ->exists();

        if (!$isEditor) {
            return response()->json([
                'message' => 'Access denied. Editor role required.',
            ], 403);
        }

        return $next($request);
    }
}