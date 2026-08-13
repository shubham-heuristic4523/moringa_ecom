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
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('name');
            $table->timestamp('starts_at')->nullable();
            // Nullable at the DB level to dodge MySQL strict-mode's "invalid
            // default value" on a NOT NULL timestamp with no default —
            // `ends_at` is still required by FlashSaleController's validation.
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
