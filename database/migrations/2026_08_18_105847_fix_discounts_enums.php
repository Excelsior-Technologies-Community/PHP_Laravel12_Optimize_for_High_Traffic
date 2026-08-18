<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE discounts MODIFY COLUMN apply_on ENUM('percentage', 'fixed') NOT NULL");
        DB::statement("ALTER TABLE discounts MODIFY COLUMN apply_to ENUM('all_products', 'specific_product') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE discounts MODIFY COLUMN apply_on ENUM('all', 'category', 'product') NOT NULL");
        DB::statement("ALTER TABLE discounts MODIFY COLUMN apply_to ENUM('percentage', 'fixed') NOT NULL");
    }
};
