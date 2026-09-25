<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Companies
        $companyA = Company::create([
            'name' => 'Company A',
            'email' => 'contact@companya.test',
            'phone' => '0600000001',
            'address' => 'Rabat',
            'status' => 'active',
        ]);

        $companyB = Company::create([
            'name' => 'Company B',
            'email' => 'contact@companyb.test',
            'phone' => '0600000002',
            'address' => 'Casablanca',
            'status' => 'active',
        ]);

        // Roles
        $adminRole = Role::create([
            'name' => 'Company Admin',
            'slug' => 'company-admin',
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
        ]);

        // Permissions
        $permissions = [
            ['name' => 'View Users', 'slug' => 'users.view'],
            ['name' => 'Create Users', 'slug' => 'users.create'],
            ['name' => 'Update Users', 'slug' => 'users.update'],
            ['name' => 'View Orders', 'slug' => 'orders.view'],
            ['name' => 'Create Orders', 'slug' => 'orders.create'],
            ['name' => 'View Planning', 'slug' => 'planning.view'],
            ['name' => 'Validate Planning', 'slug' => 'planning.validate'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Give all permissions to Company Admin
        $adminRole->permissions()->attach(
            Permission::pluck('id')->toArray()
        );

        // Give limited permissions to User
        $userRole->permissions()->attach(
            Permission::whereIn('slug', [
                'orders.view',
                'orders.create',
                'planning.view',
            ])->pluck('id')->toArray()
        );

        // Company A users
        $adminA = User::create([
            'company_id' => $companyA->id,
            'name' => 'Admin A',
            'email' => 'admin@companya.test',
            'password' => Hash::make('password123'),
        ]);

        $userA = User::create([
            'company_id' => $companyA->id,
            'name' => 'User A',
            'email' => 'user@companya.test',
            'password' => Hash::make('password123'),
        ]);

        // Company B users
        $adminB = User::create([
            'company_id' => $companyB->id,
            'name' => 'Admin B',
            'email' => 'admin@companyb.test',
            'password' => Hash::make('password123'),
        ]);

        $userB = User::create([
            'company_id' => $companyB->id,
            'name' => 'User B',
            'email' => 'user@companyb.test',
            'password' => Hash::make('password123'),
        ]);

        // Assign roles
        $adminA->roles()->attach($adminRole);
        $userA->roles()->attach($userRole);

        $adminB->roles()->attach($adminRole);
        $userB->roles()->attach($userRole);
    }
}