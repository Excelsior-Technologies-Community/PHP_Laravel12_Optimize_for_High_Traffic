<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 12, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('stock');
            $table->unsignedInteger('sold')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();

            $table->index('status');
            $table->index(['start_date', 'end_date']);
            $table->index('product_id');
            $table->index('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
