<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(
        Request $request,
        AuditLogService $auditLogService
    ) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with([
            'company',
            'roles.permissions',
        ])
            ->where('email', $credentials['email'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Check credentials
        |--------------------------------------------------------------------------
        */

        if (
            !$user ||
            !Hash::check(
                $credentials['password'],
                $user->password
            )
        ) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Check account status
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'This account is disabled.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Audit login
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'login',
            'User',
            $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | Create Sanctum token
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('auth-token')
            ->plainTextToken;

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' => 'Connexion réussie.',

            'token' => $token,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,

                'company' => $user->company,

                'roles' => $user->roles,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Current authenticated user
    |--------------------------------------------------------------------------
    */

    public function me(Request $request)
    {
        $user = $request->user()->load([
            'company',
            'roles.permissions',
        ]);

        return response()->json([
            'user' => $user,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request,
        AuditLogService $auditLogService
    ) {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Audit logout
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'logout',
            'User',
            $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | Delete current token
        |--------------------------------------------------------------------------
        */

        $currentToken = $user->currentAccessToken();

        if ($currentToken) {
            $currentToken->delete();
        }

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ]);
    }
}