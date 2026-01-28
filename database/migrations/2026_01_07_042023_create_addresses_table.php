<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // future use: user_id (optional)
            // $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('full_name');
            $table->string('mobile', 15);
            $table->string('address');      // full address
            $table->string('nearby')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};

