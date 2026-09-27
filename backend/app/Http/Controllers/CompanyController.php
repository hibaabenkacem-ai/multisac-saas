<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('id', 'desc')->get();

        return response()->json([
            'companies' => $companies,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Company created successfully.',
            'company' => $company,
        ], 201);
    }

    public function show(Company $company)
    {
        return response()->json([
            'company' => $company,
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', 'in:active,disabled'],
        ]);

        $company->update($validated);

        return response()->json([
            'message' => 'Company updated successfully.',
            'company' => $company,
        ]);
    }

    public function disable(Company $company)
    {
        $company->update([
            'status' => 'disabled',
        ]);

        return response()->json([
            'message' => 'Company disabled successfully.',
            'company' => $company,
        ]);
    }
}