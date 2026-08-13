<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('store_slug')->nullable()->unique()->after('referral_code');
        });

        DB::table('users')
            ->whereIn('role', ['admin', 'super_admin'])
            ->whereNull('store_slug')
            ->orderBy('id')
            ->get(['id', 'name'])
            ->each(function ($user) {
                $base = Str::slug($user->name) ?: 'store';
                $slug = $base;
                $suffix = 1;

                while (DB::table('users')->where('store_slug', $slug)->exists()) {
                    $slug = $base . '-' . $suffix++;
                }

                DB::table('users')->where('id', $user->id)->update(['store_slug' => $slug]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('store_slug');
        });
    }
};
