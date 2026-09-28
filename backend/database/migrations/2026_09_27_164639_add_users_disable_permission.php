<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissionId = DB::table('permissions')->insertGetId([
            'name' => 'Disable Users',
            'slug' => 'users.disable',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminRoleId = DB::table('roles')
            ->where('slug', 'company-admin')
            ->value('id');

        $editorRoleId = DB::table('roles')
            ->where('slug', 'editor')
            ->value('id');

        if ($adminRoleId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId,
            ]);
        }

        if ($editorRoleId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => $editorRoleId,
            ]);
        }
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')
            ->where('slug', 'users.disable')
            ->value('id');

        if ($permissionId) {
            DB::table('permission_role')
                ->where('permission_id', $permissionId)
                ->delete();

            DB::table('permissions')
                ->where('id', $permissionId)
                ->delete();
        }
    }
};