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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('subtotal');
            $table->string('coupon_code')->nullable()->after('discount');
            $table->foreignId('offer_id')->nullable()->after('coupon_code')->constrained('offers')->nullOnDelete();
            $table->string('payment_method')->default('cod')->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('offer_id');
            $table->dropColumn(['discount', 'coupon_code', 'payment_method']);
        });
    }
};
