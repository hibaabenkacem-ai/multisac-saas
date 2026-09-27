<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Companies
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | 2. Roles
        |--------------------------------------------------------------------------
        */

        $editorRole = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);

        $adminRole = Role::create([
            'name' => 'Company Admin',
            'slug' => 'company-admin',
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | 3. Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            [
                'name' => 'View Companies',
                'slug' => 'companies.view',
            ],
            [
                'name' => 'Create Companies',
                'slug' => 'companies.create',
            ],
            [
                'name' => 'Update Companies',
                'slug' => 'companies.update',
            ],
            [
                'name' => 'Disable Companies',
                'slug' => 'companies.disable',
            ],

            [
                'name' => 'View Users',
                'slug' => 'users.view',
            ],
            [
                'name' => 'Create Users',
                'slug' => 'users.create',
            ],
            [
                'name' => 'Update Users',
                'slug' => 'users.update',
            ],

            [
                'name' => 'View Orders',
                'slug' => 'orders.view',
            ],
            [
                'name' => 'Create Orders',
                'slug' => 'orders.create',
            ],

            [
                'name' => 'View Planning',
                'slug' => 'planning.view',
            ],
            [
                'name' => 'Validate Planning',
                'slug' => 'planning.validate',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Permissions -> Editor
        |--------------------------------------------------------------------------
        */

        $editorRole->permissions()->attach(
            Permission::whereIn('slug', [
                'companies.view',
                'companies.create',
                'companies.update',
                'companies.disable',
            ])->pluck('id')->toArray()
        );


        /*
        |--------------------------------------------------------------------------
        | 5. Permissions -> Company Admin
        |--------------------------------------------------------------------------
        */

        $adminRole->permissions()->attach(
            Permission::whereIn('slug', [
                'users.view',
                'users.create',
                'users.update',
                'orders.view',
                'orders.create',
                'planning.view',
                'planning.validate',
            ])->pluck('id')->toArray()
        );


        /*
        |--------------------------------------------------------------------------
        | 6. Permissions -> User
        |--------------------------------------------------------------------------
        */

        $userRole->permissions()->attach(
            Permission::whereIn('slug', [
                'orders.view',
                'orders.create',
                'planning.view',
            ])->pluck('id')->toArray()
        );


        /*
        |--------------------------------------------------------------------------
        | 7. Platform Editor
        |--------------------------------------------------------------------------
        */

        $editor = User::create([
            'company_id' => null,
            'name' => 'Platform Editor',
            'email' => 'editor@multisac.test',
            'password' => Hash::make('password123'),
        ]);

        $editor->roles()->attach($editorRole);


        /*
        |--------------------------------------------------------------------------
        | 8. Company A Admin
        |--------------------------------------------------------------------------
        */

        $adminA = User::create([
            'company_id' => $companyA->id,
            'name' => 'Admin A',
            'email' => 'admin.a@companya.test',
            'password' => Hash::make('password123'),
        ]);

        $adminA->roles()->attach($adminRole);


        /*
        |--------------------------------------------------------------------------
        | 9. Company A User
        |--------------------------------------------------------------------------
        */

        $userA = User::create([
            'company_id' => $companyA->id,
            'name' => 'User A',
            'email' => 'user.a@companya.test',
            'password' => Hash::make('password123'),
        ]);

        $userA->roles()->attach($userRole);


        /*
        |--------------------------------------------------------------------------
        | 10. Company B Admin
        |--------------------------------------------------------------------------
        */

        $adminB = User::create([
            'company_id' => $companyB->id,
            'name' => 'Admin B',
            'email' => 'admin.b@companyb.test',
            'password' => Hash::make('password123'),
        ]);

        $adminB->roles()->attach($adminRole);


        /*
        |--------------------------------------------------------------------------
        | 11. Company B User
        |--------------------------------------------------------------------------
        */

        $userB = User::create([
            'company_id' => $companyB->id,
            'name' => 'User B',
            'email' => 'user.b@companyb.test',
            'password' => Hash::make('password123'),
        ]);

        $userB->roles()->attach($userRole);


        /*
        |--------------------------------------------------------------------------
        | 12. Orders - Company A
        |--------------------------------------------------------------------------
        */

        Order::create([
            'company_id' => $companyA->id,
            'reference' => 'ORD-A-001',
            'status' => 'pending',
        ]);

        Order::create([
            'company_id' => $companyA->id,
            'reference' => 'ORD-A-002',
            'status' => 'completed',
        ]);


        /*
        |--------------------------------------------------------------------------
        | 13. Orders - Company B
        |--------------------------------------------------------------------------
        */

        Order::create([
            'company_id' => $companyB->id,
            'reference' => 'ORD-B-001',
            'status' => 'pending',
        ]);

        Order::create([
            'company_id' => $companyB->id,
            'reference' => 'ORD-B-002',
            'status' => 'completed',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Finished
        |--------------------------------------------------------------------------
        */

        $this->command->info('Database seeded successfully!');
        $this->command->info('Editor: editor@multisac.test / password123');
        $this->command->info('Admin A: admin.a@companya.test / password123');
        $this->command->info('User A: user.a@companya.test / password123');
        $this->command->info('Admin B: admin.b@companyb.test / password123');
        $this->command->info('User B: user.b@companyb.test / password123');
    }
}