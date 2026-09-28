<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List companies
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $companies = Company::orderBy('id', 'desc')->get();

        return response()->json([
            'companies' => $companies,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Create company
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        AuditLogService $auditLogService
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'company.created',
            'Company',
            $company->id
        );

        return response()->json([
            'message' => 'Company created successfully.',
            'company' => $company,
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | Show company
    |--------------------------------------------------------------------------
    */

    public function show(Company $company)
    {
        return response()->json([
            'company' => $company,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update company
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Company $company,
        AuditLogService $auditLogService
    ) {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'sometimes',
                'required',
                'in:active,disabled',
            ],
        ]);

        $company->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'company.updated',
            'Company',
            $company->id
        );

        return response()->json([
            'message' => 'Company updated successfully.',
            'company' => $company->fresh(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Disable company
    |--------------------------------------------------------------------------
    */

    public function disable(
        Request $request,
        Company $company,
        AuditLogService $auditLogService
    ) {
        $company->update([
            'status' => 'disabled',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $auditLogService->log(
            $request,
            'company.disabled',
            'Company',
            $company->id
        );

        return response()->json([
            'message' => 'Company disabled successfully.',
            'company' => $company->fresh(),
        ]);
    }
}