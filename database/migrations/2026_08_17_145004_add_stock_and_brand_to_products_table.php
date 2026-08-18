<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('stock')->default(0)->after('price');
            $table->foreignId('brand_id')->nullable()->after('stock')->constrained()->nullOnDelete();
            $table->string('sku')->nullable()->unique()->after('brand_id');
            $table->decimal('weight', 8, 2)->nullable()->after('sku');
            $table->string('dimensions')->nullable()->after('weight');
            $table->boolean('is_track_stock')->default(true)->after('dimensions');
            $table->boolean('is_featured')->default(false)->after('is_track_stock');

            $table->index('stock');
            $table->index('brand_id');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'stock',
                'brand_id',
                'sku',
                'weight',
                'dimensions',
                'is_track_stock',
                'is_featured',
            ]);
            $table->dropIndex(['stock']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['is_featured']);
        });
    }
};
