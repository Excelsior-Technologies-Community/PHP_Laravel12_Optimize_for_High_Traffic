<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traffic_statistics', function (Blueprint $table) {
            $table->id();

            $table->date('stat_date');
            $table->unsignedInteger('stat_minute');

            $table->unsignedBigInteger('total_requests')->default(0);
            $table->unsignedBigInteger('blocked_requests')->default(0);

            $table->unsignedBigInteger('public_requests')->default(0);
            $table->unsignedBigInteger('customer_requests')->default(0);
            $table->unsignedBigInteger('admin_requests')->default(0);

            $table->unsignedBigInteger('total_response_time')->default(0);
            $table->unsignedInteger('max_response_time')->default(0);

            $table->timestamps();

            $table->unique(
                ['stat_date', 'stat_minute'],
                'traffic_statistics_date_minute_unique'
            );

            $table->index('stat_date');
            $table->index('blocked_requests');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_statistics');
    }
};