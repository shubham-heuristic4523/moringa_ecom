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
        Schema::table('referrals', function (Blueprint $table) {
            // How much was credited to the referred person's wallet. Nullable —
            // legacy referrals from before the wallet system used reward_offer_id
            // instead, which stays in place for that history.
            $table->decimal('reward_amount', 10, 2)->nullable()->after('reward_offer_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('wallet_used', 10, 2)->default(0)->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn('reward_amount');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('wallet_used');
        });
    }
};
