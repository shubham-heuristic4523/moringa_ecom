<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name')->default('Moringa');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();

            $table->string('primary_color', 7)->default('#3E8E5B');
            $table->string('secondary_color', 7)->default('#F5A623');
            $table->string('accent_color', 7)->default('#2C6E49');

            $table->timestamps();
        });

        // Singleton row — the app always reads/writes id = 1.
        DB::table('site_settings')->insert([
            'site_name' => 'Moringa',
            'tagline' => 'Pure, natural moringa products for everyday wellness.',
            'primary_color' => '#3E8E5B',
            'secondary_color' => '#F5A623',
            'accent_color' => '#2C6E49',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
