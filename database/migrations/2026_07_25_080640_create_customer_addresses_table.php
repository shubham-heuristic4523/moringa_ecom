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
        Schema::create('customer_addresses', function (Blueprint $table) {

            $table->id();

            // User Relation
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Address Details
            $table->string('full_name');
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();

            // Address Type
            $table->enum('address_type', [
                'home',
                'office',
                'other'
            ])->default('home');

            // Address
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('landmark')->nullable();

            // Location
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('India');
            $table->string('postal_code', 10);

            // Default Address
            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};