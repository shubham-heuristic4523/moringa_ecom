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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Order Identification
            $table->string('order_number')->nullable()->unique();

            // Relations
            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('address_id')
                ->nullable()
                ->constrained('customer_addresses')
                ->nullOnDelete();

            // Order Status
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'packed',
                'shipped',
                'out_for_delivery',
                'delivered',
                'cancelled',
                'returned',
                'refunded',
            ])->default('pending');

            // Amounts
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Notes
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();

            // Cancellation
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
