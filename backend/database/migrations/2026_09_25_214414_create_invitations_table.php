<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->string('email');

            $table->string('token_hash');

            $table->timestamp('expires_at');

            $table->timestamp('accepted_at')->nullable();

            $table->timestamps();

            $table->index(['email', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};