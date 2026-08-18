<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Cart;
use App\Models\WishlistItem;
use App\Models\ProductReview;
use App\Models\RecentlyViewedProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HighTrafficSeeder extends Seeder
{

    const CUSTOMERS  = 500;
    const PRODUCTS   = 1000;
    const ORDERS     = 2000;
    const CHUNK_SIZE = 200; // DB insert chunk size

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->clearOldData();

        echo "⏳ Seeding base data...\n";
        $sizes      = $this->seedSizes();
        $colors     = $this->seedColors();
        $categories = $this->seedCategories();
        $brands     = $this->seedBrands();

        echo "⏳ Seeding " . self::CUSTOMERS . " customers...\n";
        $customerIds = $this->seedCustomers();

        echo "⏳ Seeding " . self::PRODUCTS . " products...\n";
        $productIds = $this->seedProducts($brands, $sizes, $colors, $categories);

        echo "⏳ Seeding " . self::ORDERS . " orders...\n";
        $this->seedOrdersAndItems($customerIds, $productIds, $sizes, $colors, $categories);

        echo "⏳ Seeding wishlists, reviews, recently viewed...\n";
        $this->seedWishlists($customerIds, $productIds);
        $this->seedReviews($customerIds, $productIds);
        $this->seedRecentlyViewed($customerIds, $productIds);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        echo "✅ High Traffic Seeding Done!\n";
        echo "   Customers : " . self::CUSTOMERS . "\n";
        echo "   Products  : " . self::PRODUCTS . "\n";
        echo "   Orders    : " . self::ORDERS . "\n";
    }

    // ─────────────────────────────────────────
    private function randSubset(array $arr, int $count): array
    {
        $count = min($count, count($arr));
        $keys  = (array) array_rand($arr, $count);
        return array_values(array_intersect_key($arr, array_flip($keys)));
    }

    // ─────────────────────────────────────────
    private function clearOldData(): void
    {
        $tables = [
            'recently_viewed_products', 'wishlist_items', 'product_reviews',
            'order_items', 'orders', 'carts', 'addresses',
            'customers', 'products',
        ];
        foreach ($tables as $t) {
            try { DB::table($t)->truncate(); } catch (\Exception $e) {}
        }
        echo "🗑️  Old data cleared.\n";
    }

    // ─────────────────────────────────────────
    private function seedSizes(): array
    {
        $names = ['S', 'M', 'L', 'XL', 'XXL', '3XL', 'Free Size', '28', '30', '32'];
        Size::truncate();
        foreach ($names as $n) Size::firstOrCreate(['size_name' => $n]);
        return Size::pluck('id')->toArray();
    }

    private function seedColors(): array
    {
        $names = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple', 'Orange', 'Gray'];
        Color::truncate();
        foreach ($names as $n) Color::firstOrCreate(['color_name' => $n]);
        return Color::pluck('id')->toArray();
    }

    private function seedCategories(): array
    {
        $names = ['Men', 'Women', 'Kids', 'Electronics', 'Home', 'Sports', 'Books', 'Beauty', 'Toys', 'Food'];
        Category::truncate();
        foreach ($names as $n) Category::firstOrCreate(['category_name' => $n]);
        return Category::pluck('id')->toArray();
    }

    private function seedBrands(): array
    {
        $names = ['Nike', 'Adidas', 'Puma', "Levi's", 'Zara', 'H&M', 'Samsung', 'Sony', 'Apple', 'Dell'];
        Brand::truncate();
        foreach ($names as $n) {
            Brand::firstOrCreate(['slug' => Str::slug($n)], [
                'name'   => $n,
                'image'  => 'https://placehold.co/400x400?text=' . urlencode($n),
                'status' => 'active',
            ]);
        }
        return Brand::pluck('id')->toArray();
    }

    // ─────────────────────────────────────────
    private function seedCustomers(): array
    {
        $password = Hash::make('password123');
        $rows = [];
        $now  = now();

        for ($i = 1; $i <= self::CUSTOMERS; $i++) {
            $rows[] = [
                'name'       => "Customer $i",
                'email'      => "customer$i@test.com",
                'password'   => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, self::CHUNK_SIZE) as $chunk) {
            DB::table('customers')->insert($chunk);
        }

        return DB::table('customers')->pluck('id')->toArray();
    }

    // ─────────────────────────────────────────
    private function seedProducts(array $brands, array $sizes, array $colors, array $categories): array
    {
        $baseNames = [
            'T-Shirt', 'Jeans', 'Shoes', 'Jacket', 'Hoodie', 'Shirt', 'Pants',
            'Sneakers', 'Sweater', 'Shorts', 'Polo', 'Blazer', 'Joggers', 'Tank Top',
            'Cardigan', 'Boots', 'Dress', 'Skirt', 'Coat', 'Vest',
        ];

        $rows = [];
        $now  = now();

        for ($i = 1; $i <= self::PRODUCTS; $i++) {
            $baseName = $baseNames[($i - 1) % count($baseNames)];
            $name     = $baseName . ' ' . $i;

            $rows[] = [
                'name'       => $name,
                'details'    => "Premium quality $name. Comfortable and durable for everyday use.",
                'price'      => rand(199, 4999),
                'image'      => 'https://placehold.co/600x600?text=' . urlencode($name),
                'sizes'      => json_encode($this->randSubset($sizes, rand(2, 5))),
                'colors'     => json_encode($this->randSubset($colors, rand(2, 4))),
                'categories' => json_encode($this->randSubset($categories, rand(1, 3))),
                'status'     => 'active',
                'stock'      => rand(0, 200),
                'brand_id'   => $brands[array_rand($brands)],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, self::CHUNK_SIZE) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        return DB::table('products')->pluck('id')->toArray();
    }

    // ─────────────────────────────────────────
    private function seedOrdersAndItems(array $customerIds, array $productIds, array $sizes, array $colors, array $categories): void
    {
        $now = now();

        // Addresses batch insert
        $addressRows = [];
        foreach ($customerIds as $cId) {
            $addressRows[] = [
                'customer_id' => $cId,
                'full_name'   => "Customer $cId",
                'mobile'      => '9' . rand(100000000, 999999999),
                'address'     => rand(1, 999) . ' Main Street',
                'nearby'      => 'Near City Mall',
                'city'        => 'Ahmedabad',
                'state'       => 'Gujarat',
                'pincode'     => '38000' . rand(1, 9),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }
        foreach (array_chunk($addressRows, self::CHUNK_SIZE) as $chunk) {
            DB::table('addresses')->insert($chunk);
        }

        // address_id per customer
        $addressMap = DB::table('addresses')->pluck('id', 'customer_id')->toArray();

        // Orders + OrderItems
        $orderRows     = [];
        $orderItemRows = [];
        $orderId       = 0;

        for ($i = 0; $i < self::ORDERS; $i++) {
            $cId       = $customerIds[array_rand($customerIds)];
            $addressId = $addressMap[$cId] ?? null;
            if (!$addressId) continue;

            $price    = rand(199, 4999);
            $qty      = rand(1, 5);
            $subtotal = $price * $qty;

            $orderRows[] = [
                'customer_id'     => $cId,
                'address_id'      => $addressId,
                'subtotal'        => $subtotal,
                'discount_amount' => 0,
                'total_price'     => $subtotal,
                'payment_method'  => rand(0, 1) ? 'COD' : 'ONLINE',
                'status'          => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
                'created_at'      => Carbon::now()->subDays(rand(0, 365)),
                'updated_at'      => $now,
            ];
        }

        // Insert orders in chunks, then get IDs
        foreach (array_chunk($orderRows, self::CHUNK_SIZE) as $chunk) {
            DB::table('orders')->insert($chunk);
        }

        $allOrderIds = DB::table('orders')->pluck('id')->toArray();

        // Order items
        foreach ($allOrderIds as $oId) {
            $pId = $productIds[array_rand($productIds)];
            $price = rand(199, 4999);
            $qty   = rand(1, 5);

            $orderItemRows[] = [
                'order_id'        => $oId,
                'product_id'      => $pId,
                'size_id'         => $sizes[array_rand($sizes)],
                'color_id'        => $colors[array_rand($colors)],
                'category_id'     => $categories[array_rand($categories)],
                'quantity'        => $qty,
                'price'           => $price,
                'discount_amount' => 0,
                'total'           => $price * $qty,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        foreach (array_chunk($orderItemRows, self::CHUNK_SIZE) as $chunk) {
            DB::table('order_items')->insert($chunk);
        }
    }

    // ─────────────────────────────────────────
    private function seedWishlists(array $customerIds, array $productIds): void
    {
        $rows = [];
        $now  = now();
        $sample = array_slice($customerIds, 0, 200); // 200 customers

        foreach ($sample as $cId) {
            $pId = $productIds[array_rand($productIds)];
            $rows[] = [
                'customer_id' => $cId,
                'product_id'  => $pId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        foreach (array_chunk($rows, self::CHUNK_SIZE) as $chunk) {
            DB::table('wishlist_items')->insert($chunk);
        }
    }

    private function seedReviews(array $customerIds, array $productIds): void
    {
        $rows    = [];
        $now     = now();
        $reviews = [
            'Excellent product!', 'Very good quality.', 'Fast delivery.',
            'Worth the price.', 'Highly recommended!', 'Good but could be better.',
            'Amazing quality!', 'Will buy again.', 'Perfect fit.', 'Love it!',
        ];
        $sample = array_slice($customerIds, 0, 300);

        foreach ($sample as $cId) {
            $rows[] = [
                'customer_id' => $cId,
                'product_id'  => $productIds[array_rand($productIds)],
                'rating'      => rand(3, 5),
                'review'      => $reviews[array_rand($reviews)],
                'status'      => 'approved',
                'admin_note'  => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        foreach (array_chunk($rows, self::CHUNK_SIZE) as $chunk) {
            DB::table('product_reviews')->insert($chunk);
        }
    }

    private function seedRecentlyViewed(array $customerIds, array $productIds): void
    {
        $rows   = [];
        $now    = now();
        $sample = array_slice($customerIds, 0, 200);

        foreach ($sample as $cId) {
            foreach (array_rand($productIds, 5) as $idx) {
                $rows[] = [
                    'customer_id' => $cId,
                    'product_id'  => $productIds[$idx],
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        foreach (array_chunk($rows, self::CHUNK_SIZE) as $chunk) {
            DB::table('recently_viewed_products')->insert($chunk);
        }
    }
}
