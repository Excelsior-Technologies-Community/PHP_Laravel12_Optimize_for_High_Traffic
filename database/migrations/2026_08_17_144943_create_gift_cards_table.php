<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('amount', 12, 2);
            $table->decimal('balance', 12, 2);
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->text('message')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->enum('status', ['active', 'used', 'expired', 'cancelled'])->default('active');
            $table->timestamps();

            $table->index('code');
            $table->index('customer_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
