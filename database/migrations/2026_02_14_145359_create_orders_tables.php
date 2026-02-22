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
        // 1. Main Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Customer reference (nullable to support guest checkout)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // Unique order number (e.g., ORD-2026-001)
            $table->string('number')->unique();
            // Payment method used: cod, stripe, paypal, etc.
            $table->string('payment_method')->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->decimal('shipping_price', 10, 2)->default(0);
            $table->decimal('tax_price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2); // Final total amount

            // Shipping information (can be moved to a separate addresses table later)
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->string('customer_city');

            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            // Save product name to ensure record integrity if the product is deleted
            $table->string('product_name');
            $table->decimal('price', 10, 2); // Price at the time of purchase
            $table->integer('quantity');
            $table->unique(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
