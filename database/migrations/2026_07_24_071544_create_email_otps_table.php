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
        Schema::create('email_otps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('email');

            // Store 6-digit OTP
            $table->string('otp', 10);

            // register, login, forgot_password, email_verification, etc.
            $table->string('purpose', 50);

            $table->timestamp('expires_at');

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('email');
            $table->index('purpose');
            $table->index('expires_at');
            $table->index(['email', 'purpose']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_otps');
    }
};