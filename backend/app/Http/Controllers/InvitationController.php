<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvitationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create invitation
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        AuditLogService $auditLogService
    ) {
        $validated = $request->validate([
            'company_id' => [
                'required',
                'exists:companies,id',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check existing user
        |--------------------------------------------------------------------------
        */

        if (
            User::where(
                'email',
                $validated['email']
            )->exists()
        ) {
            return response()->json([
                'message' =>
                    'This email already belongs to an existing user.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate token
        |--------------------------------------------------------------------------
        */

        $plainToken = Str::random(64);

        /*
        |--------------------------------------------------------------------------
        | Create invitation
        |--------------------------------------------------------------------------
        */

        $invitation = Invitation::create([
            'company_id' => $validated['company_id'],
            'role_id' => $validated['role_id'],
            'email' => $validated['email'],

            /*
            | Only the hash is stored in the database.
            */
            'token_hash' => Hash::make($plainToken),

            /*
            | Invitation valid for 72 hours.
            */
            'expires_at' => now()->addHours(72),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'invitation.created',
            'Invitation',
            $invitation->id
        );

        /*
        |--------------------------------------------------------------------------
        | Development response
        |--------------------------------------------------------------------------
        |
        | We return the token for Postman testing.
        | In production this token should be sent by email/link.
        |
        */

        return response()->json([
            'message' => 'Invitation created successfully.',

            'invitation' => [
                'id' => $invitation->id,
                'company_id' => $invitation->company_id,
                'role_id' => $invitation->role_id,
                'email' => $invitation->email,
                'expires_at' => $invitation->expires_at,

                /*
                | Plain token only in development response.
                */
                'token' => $plainToken,
            ],
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Accept invitation
    |--------------------------------------------------------------------------
    */

    public function accept(
        Request $request,
        AuditLogService $auditLogService
    ) {
        $validated = $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find valid invitations
        |--------------------------------------------------------------------------
        */

        $invitations = Invitation::whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Find matching token
        |--------------------------------------------------------------------------
        */

        $invitation = $invitations->first(
            function ($invitation) use ($validated) {

                return Hash::check(
                    $validated['token'],
                    $invitation->token_hash
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Invalid / expired invitation
        |--------------------------------------------------------------------------
        */

        if (!$invitation) {
            throw ValidationException::withMessages([
                'token' => 'Invalid or expired invitation.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check email
        |--------------------------------------------------------------------------
        */

        if (
            User::where(
                'email',
                $invitation->email
            )->exists()
        ) {
            return response()->json([
                'message' =>
                    'A user with this email already exists.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Create user
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'company_id' => $invitation->company_id,
            'name' => $validated['name'],
            'email' => $invitation->email,
            'password' => Hash::make(
                $validated['password']
            ),
            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Assign invitation role
        |--------------------------------------------------------------------------
        */

        $user->roles()->attach(
            $invitation->role_id
        );

        /*
        |--------------------------------------------------------------------------
        | Mark invitation as accepted
        |--------------------------------------------------------------------------
        */

        $invitation->update([
            'accepted_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'invitation.accepted',
            'Invitation',
            $invitation->id
        );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' =>
                'Invitation accepted successfully.',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company_id' => $user->company_id,
                'status' => $user->status,
            ],
        ], 201);
    }
}