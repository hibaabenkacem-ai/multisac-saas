<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List users
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $currentUser = $request->user();

        $query = User::with([
            'company',
            'roles',
        ])->orderBy('id', 'desc');

        /*
        |--------------------------------------------------------------------------
        | Check if current user is Editor
        |--------------------------------------------------------------------------
        */

        $isEditor = $currentUser->roles()
            ->where('slug', 'editor')
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Company isolation
        |--------------------------------------------------------------------------
        |
        | Editor can see users from all companies.
        |
        | Company Admin / User can only see users
        | belonging to their own company.
        |
        */

        if (!$isEditor) {
            $query->where(
                'company_id',
                $currentUser->company_id
            );
        }

        return response()->json([
            'users' => $query->get(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Show user
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        User $user
    ) {
        $currentUser = $request->user();

        $isEditor = $currentUser->roles()
            ->where('slug', 'editor')
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Company isolation
        |--------------------------------------------------------------------------
        */

        if (
            !$isEditor &&
            $user->company_id !== $currentUser->company_id
        ) {
            return response()->json([
                'message' =>
                    'Access denied. User belongs to another company.',
            ], 403);
        }

        return response()->json([
            'user' => $user->load([
                'company',
                'roles.permissions',
            ]),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update user
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user,
        AuditLogService $auditLogService
    ) {
        $currentUser = $request->user();

        $isEditor = $currentUser->roles()
            ->where('slug', 'editor')
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Company isolation
        |--------------------------------------------------------------------------
        */

        if (
            !$isEditor &&
            $user->company_id !== $currentUser->company_id
        ) {
            return response()->json([
                'message' =>
                    'Access denied. User belongs to another company.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'status' => [
                'sometimes',
                'required',
                'in:active,disabled',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $user->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'user.updated',
            'User',
            $user->id
        );

        return response()->json([
            'message' => 'User updated successfully.',

            'user' => $user->fresh()->load([
                'company',
                'roles',
            ]),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Disable user
    |--------------------------------------------------------------------------
    */

    public function disable(
        Request $request,
        User $user,
        AuditLogService $auditLogService
    ) {
        $currentUser = $request->user();

        $isEditor = $currentUser->roles()
            ->where('slug', 'editor')
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Company isolation
        |--------------------------------------------------------------------------
        */

        if (
            !$isEditor &&
            $user->company_id !== $currentUser->company_id
        ) {
            return response()->json([
                'message' =>
                    'Access denied. User belongs to another company.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Disable account
        |--------------------------------------------------------------------------
        */

        $user->update([
            'status' => 'disabled',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Revoke all Sanctum tokens
        |--------------------------------------------------------------------------
        */

        $user->tokens()->delete();

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'user.disabled',
            'User',
            $user->id
        );

        return response()->json([
            'message' => 'User disabled successfully.',

            'user' => $user->fresh(),
        ]);
    }
}