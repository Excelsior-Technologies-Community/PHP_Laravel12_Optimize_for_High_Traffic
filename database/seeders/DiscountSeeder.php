<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            echo "⚠️ No products found. Please run ComprehensiveDataSeeder first.\n";
            return;
        }

        $discountTitles = [
            'Summer Sale', 'Winter Special', 'New Year Discount', 'Flash Sale',
            'Festival Offer', 'Clearance Sale', 'Member Exclusive', 'First Order Discount',
            'Bulk Purchase', 'Weekend Special'
        ];

        foreach ($discountTitles as $index => $title) {
            $applyOn = $index % 2 === 0 ? 'percentage' : 'fixed';
            $applyTo = $index % 3 === 0 ? 'all_products' : 'specific_product';
            $value = $applyOn === 'percentage' ? rand(5, 50) : rand(50, 500);
            $startDate = Carbon::now()->subDays(rand(1, 10))->format('Y-m-d');
            $endDate = Carbon::now()->addDays(rand(10, 60))->format('Y-m-d');

            Discount::create([
                'title' => $title,
                'discount_code' => strtoupper(Str::random(8)),
                'apply_on' => $applyOn,
                'value' => $value,
                'apply_to' => $applyTo,
                'product_ids' => $applyTo === 'specific_product'
                    ? $products->random(rand(2, 5))->pluck('id')->toArray()
                    : null,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }

        echo "✅ 10 discounts seeded successfully!\n";
    }
}
