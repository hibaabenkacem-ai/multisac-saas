<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('subscription_status')
                ->default('trial')
                ->after('status');

            $table->timestamp('subscription_started_at')
                ->nullable()
                ->after('subscription_status');

            $table->timestamp('subscription_ends_at')
                ->nullable()
                ->after('subscription_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status',
                'subscription_started_at',
                'subscription_ends_at',
            ]);
        });
    }
};