<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traffic_block_logs', function (Blueprint $table) {
            $table->id();

            $table->string('ip_address', 45)->nullable();
            $table->string('method', 10);
            $table->string('route')->nullable();
            $table->string('url', 2048)->nullable();

            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('reason')->default('rate_limit_exceeded');

            $table->unsignedInteger('limit')->nullable();
            $table->unsignedInteger('retry_after')->nullable();

            $table->timestamps();

            $table->index('ip_address');
            $table->index('route');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_block_logs');
    }
};