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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->enum('referral_discount_type', ['percentage', 'flat'])->default('percentage')->after('accent_color');
            $table->decimal('referral_discount_value', 10, 2)->default(10)->after('referral_discount_type');
            $table->decimal('referral_max_discount_amount', 10, 2)->nullable()->default(200)->after('referral_discount_value');
            $table->unsignedInteger('referral_validity_days')->default(30)->after('referral_max_discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'referral_discount_type',
                'referral_discount_value',
                'referral_max_discount_amount',
                'referral_validity_days',
            ]);
        });
    }
};
