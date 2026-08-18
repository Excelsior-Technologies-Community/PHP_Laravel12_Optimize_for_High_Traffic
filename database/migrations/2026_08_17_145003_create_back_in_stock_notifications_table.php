<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('back_in_stock_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('notified')->default(false);
            $table->timestamps();

            $table->index('customer_id');
            $table->index('product_id');
            $table->index('product_variant_id');
            $table->index('notified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('back_in_stock_notifications');
    }
};
