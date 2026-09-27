<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE orders ENABLE ROW LEVEL SECURITY');

        DB::statement("
            CREATE POLICY orders_company_policy
            ON orders
            USING (
                company_id = current_setting('app.company_id', true)::bigint
            )
            WITH CHECK (
                company_id = current_setting('app.company_id', true)::bigint
            )
        ");
    }

    public function down(): void
    {
        DB::statement('DROP POLICY IF EXISTS orders_company_policy ON orders');

        DB::statement('ALTER TABLE orders DISABLE ROW LEVEL SECURITY');
    }
};