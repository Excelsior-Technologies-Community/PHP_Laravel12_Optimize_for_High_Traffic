<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('referral_code')->unique();
            $table->foreignId('referred_by')->nullable()->constrained('customers')->nullOnDelete();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('max_uses')->default(10);
            $table->dateTime('expires_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('referral_code');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
