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
        Schema::table('users', function (Blueprint $table) {
            // Which admin's storefront this customer signed up on, if any.
            // Null means they registered via the generic platform "/" — not
            // tied to a specific admin's site.
            $table->foreignId('registered_via_admin_id')->nullable()->after('store_slug')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('registered_via_admin_id');
        });
    }
};
