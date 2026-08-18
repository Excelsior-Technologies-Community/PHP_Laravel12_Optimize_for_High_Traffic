<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('size_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained()->nullOnDelete();
            $table->json('measurements');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index('size_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('size_guides');
    }
};
