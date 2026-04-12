<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'is_active'], 'users_role_active_idx');
            $table->index('email_verified_at', 'users_verified_idx');
            $table->index('last_login_at', 'users_last_login_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_active_idx');
            $table->dropIndex('users_verified_idx');
            $table->dropIndex('users_last_login_idx');
        });
    }
};
