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
        //
        Schema::table('products', function (Blueprint $table) {

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('slug')->unique();

            $table->string('sku')->nullable()->unique();

            $table->text('short_description')->nullable();

            $table->longText('ingredients')->nullable();

            $table->longText('who_can_use')->nullable();

            $table->longText('how_to_use')->nullable();

            $table->longText('product_features')->nullable();

            $table->decimal('regular_price', 10, 2)->nullable();

            $table->decimal('sale_price', 10, 2)->nullable();

            $table->string('thumbnail')->nullable();

            $table->decimal('average_rating', 3, 2)->default(0);

            $table->integer('total_reviews')->default(0);

            $table->boolean('is_featured')->default(false);

            $table->boolean('is_best_seller')->default(false);

            $table->boolean('is_new')->default(false);

            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->string('meta_title')->nullable();

            $table->text('meta_keywords')->nullable();

            $table->text('meta_description')->nullable();

            $table->dropColumn([
                'price',
                'quantity'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->decimal('price', 10, 2)->default(0)->after('description');

            $table->integer('quantity')->default(0)->after('price');

            $table->dropConstrainedForeignId('brand_id');

            $table->dropConstrainedForeignId('category_id');

            $table->dropUnique(['slug']);

            $table->dropColumn([
                'slug',
                'sku',
                'short_description',
                'ingredients',
                'who_can_use',
                'how_to_use',
                'product_features',
                'regular_price',
                'sale_price',
                'thumbnail',
                'average_rating',
                'total_reviews',
                'is_featured',
                'is_best_seller',
                'is_new',
                'status',
                'meta_title',
                'meta_keywords',
                'meta_description',
            ]);
        });
    }
};
