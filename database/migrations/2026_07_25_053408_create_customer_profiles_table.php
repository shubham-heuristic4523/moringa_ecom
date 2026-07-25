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
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->date('date_of_birth')->nullable();

            $table->string('profile_photo')->nullable();

            $table->string('company_name')->nullable();

            $table->string('gst_number')->nullable();

            $table->text('bio')->nullable();

            $table->boolean('profile_completed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
