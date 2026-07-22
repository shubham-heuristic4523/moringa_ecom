<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();

            // Authentication
            $table->string('password')->nullable();
            $table->string('google_id')->nullable()->unique();

            // Role Management
            $table->enum('role', [
                'super_admin',
                'admin',
                'user'
            ])->default('user');

            // Account Status
            $table->enum('status', [
                'active',
                'inactive',
                'blocked'
            ])->default('active');

            $table->timestamp('email_verified_at')->nullable();

            // Login Tracking
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};