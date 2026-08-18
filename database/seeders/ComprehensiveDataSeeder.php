<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\ProductTag;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\FlashSale;
use App\Models\SizeGuide;
use App\Models\ProductReview;
use App\Models\ReturnRequest;
use App\Models\GiftCard;
use App\Models\WishlistItem;
use App\Models\BackInStockNotification;
use App\Models\RecentlyViewedProduct;
use App\Models\ProductComparison;
use App\Models\Referral;
use App\Models\CustomerWallet;
use App\Models\WalletTransaction;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->truncateTables();
        $customers = collect($this->seedCustomers());
        $sizes = collect($this->seedSizes());
        $colors = collect($this->seedColors());
        $categories = collect($this->seedCategories());
        $brands = collect($this->seedBrands());
        $tags = collect($this->seedProductTags());
        $products = collect($this->seedProducts($brands, $sizes, $colors, $categories, $tags));
        $this->seedProductImages($products);
        $this->seedProductVariants($products, $sizes, $colors, $categories);
        $this->seedFlashSales($products);
        $this->seedSizeGuides($products, $sizes);
        $this->seedProductReviews($products, $customers);
        $this->seedReturnRequests($products, $customers);
        $this->seedGiftCards($customers);
        $this->seedWishlistItems($products, $customers);
        $this->seedBackInStockNotifications($products, $customers);
        $this->seedRecentlyViewed($products, $customers);
        $this->seedComparisons($products, $customers);
        $this->seedReferrals($customers);
        $this->seedWallets($customers);
        $this->seedDiscounts($products);

        echo "✅ All data seeded successfully!\n";
    }

    private function truncateTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            'wallet_transactions',
            'gift_card_transactions',
            'back_in_stock_notifications',
            'product_comparisons',
            'recently_viewed_products',
            'wishlist_items',
            'product_images',
            'return_requests',
            'product_reviews',
            'flash_sales',
            'size_guides',
            'product_variants',
            'product_tag_product',
            'gift_cards',
            'referrals',
            'customer_wallets',
            'products',
            'brands',
            'product_tags',
            'order_items',
            'orders',
            'addresses',
            'carts',
            'customers',
            'categories',
            'colors',
            'sizes',
            'discounts',
        ];

        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
            } catch (\Exception $e) {
                echo "⚠️ Could not truncate $table: " . $e->getMessage() . "\n";
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function seedCustomers(): array
    {
        $customers = [];
        for ($i = 1; $i <= 10; $i++) {
            $customers[] = Customer::create([
                'name' => "Customer $i",
                'email' => "customer$i@example.com",
                'password' => bcrypt('password123'),
                'profile_image' => null,
            ]);
        }
        return $customers;
    }

    private function seedSizes(): array
    {
        $sizeNames = ['S', 'M', 'L', 'XL', 'XXL', '3XL', 'Free Size', '28', '30', '32'];
        $sizes = [];
        foreach ($sizeNames as $name) {
            $sizes[] = Size::create(['size_name' => $name]);
        }
        return $sizes;
    }

    private function seedColors(): array
    {
        $colorNames = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple', 'Orange', 'Gray'];
        $colors = [];
        foreach ($colorNames as $name) {
            $colors[] = Color::create(['color_name' => $name]);
        }
        return $colors;
    }

    private function seedCategories(): array
    {
        $categoryNames = ['Men', 'Women', 'Kids', 'Electronics', 'Home', 'Sports', 'Books', 'Beauty', 'Toys', 'Food'];
        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[] = Category::create(['category_name' => $name]);
        }
        return $categories;
    }

    private function seedBrands(): array
    {
        $brandNames = [
            'Nike', 'Adidas', 'Puma', 'Levi\'s', 'Zara',
            'H&M', 'Samsung', 'Sony', 'Apple', 'Dell'
        ];
        $brands = [];
        foreach ($brandNames as $name) {
            $brands[] = Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'image' => 'https://placehold.co/400x400?text=' . urlencode($name),
                'description' => "Official $name brand products with premium quality.",
                'status' => 'active',
            ]);
        }
        return $brands;
    }

    private function seedProductTags(): array
    {
        $tagNames = [
            'New Arrival', 'Best Seller', 'Trending', 'Sale', 'Premium',
            'Eco-Friendly', 'Limited Edition', 'Exclusive', 'Top Rated', 'Recommended'
        ];
        $tags = [];
        foreach ($tagNames as $name) {
            $tags[] = ProductTag::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
        return $tags;
    }

    private function seedProducts($brands, $sizes, $colors, $categories, $tags): array
    {
        $products = [];
        $productNames = [
            'Classic T-Shirt', 'Slim Fit Jeans', 'Running Shoes', 'Leather Jacket', 'Cotton Hoodie',
            'Formal Shirt', 'Chino Pants', 'Sneakers', 'Denim Jacket', 'Wool Sweater',
            'Linen Shorts', 'Polo Shirt', 'Blazer', 'Joggers', 'Tank Top',
            'Cardigan', 'Cargo Pants', 'Bomber Jacket', 'Sweatshirt', 'Windbreaker',
            'Graphic Tee', 'Yoga Pants', 'Hiking Boots', 'Swim Trunks', 'Linen Shirt',
            'Fleece Jacket', 'Cargo Shorts', 'Athletic Shoes', 'Pajama Set', 'Beanie'
        ];

        foreach ($productNames as $index => $name) {
            $product = Product::create([
                'name' => $name,
                'details' => "High-quality $name made from premium materials. Comfortable, durable, and stylish.",
                'price' => rand(299, 4999),
                'image' => 'https://placehold.co/600x600?text=' . urlencode($name),
                'sizes' => $sizes->random(rand(3, 6))->pluck('id')->toArray(),
                'colors' => $colors->random(rand(2, 5))->pluck('id')->toArray(),
                'categories' => $categories->random(rand(1, 3))->pluck('id')->toArray(),
                'status' => 'active',
                'stock' => rand(0, 100),
                'brand_id' => $brands->random()->id,
                'sku' => 'PROD-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'weight' => rand(100, 2000),
                'dimensions' => rand(10, 50) . 'x' . rand(10, 50) . 'x' . rand(5, 20),
                'is_track_stock' => true,
                'is_featured' => rand(0, 1),
            ]);

            $product->tags()->attach($tags->random(rand(2, 4))->pluck('id'));
            $products[] = $product;
        }

        return $products;
    }

    private function seedProductImages($products): void
    {
        foreach ($products as $product) {
            foreach (range(1, rand(3, 5)) as $i) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'https://placehold.co/600x600?text=' . urlencode($product->name) . '++' . $i,
                    'sort_order' => $i,
                ]);
            }
        }
    }

    private function seedProductVariants($products, $sizes, $colors, $categories): void
    {
        $selectedProducts = $products->random(10);
        foreach ($selectedProducts as $product) {
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => 'VAR-' . str_pad($product->id, 6, '0', STR_PAD_LEFT),
                'size_id' => $sizes->random()->id,
                'color_id' => $colors->random()->id,
                'category_id' => $categories->random()->id,
                'price' => $product->price + rand(-100, 500),
                'stock' => rand(0, 50),
                'image' => 'https://placehold.co/400x400?text=' . urlencode($product->name) . '+Variant',
                'status' => 'active',
            ]);
        }
    }

    private function seedFlashSales($products): void
    {
        $now = Carbon::now();
        $selectedProducts = $products->random(10);
        foreach ($selectedProducts as $product) {
            FlashSale::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'discount_type' => rand(0, 1) ? 'percentage' : 'fixed',
                'discount_value' => rand(10, 50),
                'start_date' => $now->copy()->subDays(rand(1, 5)),
                'end_date' => $now->copy()->addDays(rand(1, 10)),
                'stock' => rand(10, 100),
                'sold' => rand(0, 50),
                'status' => 'active',
            ]);
        }
    }

    private function seedSizeGuides($products, $sizes): void
    {
        $selectedProducts = $products->random(10);
        foreach ($selectedProducts as $product) {
            SizeGuide::create([
                'product_id' => $product->id,
                'size_id' => $sizes->random()->id,
                'measurements' => [
                    'chest' => rand(80, 120),
                    'waist' => rand(60, 100),
                    'length' => rand(60, 100),
                    'shoulder' => rand(35, 50),
                    'sleeve' => rand(50, 80),
                ],
                'description' => 'Please refer to this size guide before ordering.',
            ]);
        }
    }

    private function seedProductReviews($products, $customers): void
    {
        $selectedProducts = $products->random(10);
        foreach ($selectedProducts as $product) {
            ProductReview::create([
                'product_id' => $product->id,
                'customer_id' => $customers->random()->id,
                'rating' => rand(3, 5),
                'review' => 'Great product! Quality is excellent and delivery was fast. Highly recommended.',
                'status' => 'approved',
                'admin_note' => null,
            ]);
        }
    }

    private function seedReturnRequests($products, $customers): void
    {
        $selectedProducts = $products->random(10);
        foreach ($selectedProducts as $product) {
            $customer = $customers->random();

            $address = \App\Models\Address::create([
                'customer_id' => $customer->id,
                'full_name' => $customer->name,
                'mobile' => '99999' . rand(1000, 9999),
                'address' => '123 Main Street, Apt ' . rand(1, 50),
                'nearby' => 'Near City Mall',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380001',
            ]);

            $order = Order::create([
                'customer_id' => $customer->id,
                'address_id' => $address->id,
                'subtotal' => $product->price,
                'discount_amount' => 0,
                'total_price' => $product->price,
                'payment_method' => 'COD',
                'status' => 'delivered',
            ]);

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'size_id' => $product->sizes[0] ?? 1,
                'color_id' => $product->colors[0] ?? 1,
                'category_id' => $product->categories[0] ?? 1,
                'quantity' => 1,
                'price' => $product->price,
                'total' => $product->price,
            ]);

            ReturnRequest::create([
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'customer_id' => $customer->id,
                'reason' => 'Product received with minor defects. Requesting return.',
                'status' => 'pending',
                'admin_note' => null,
            ]);
        }
    }

    private function seedGiftCards($customers): void
    {
        $selectedCustomers = $customers->random(10);
        foreach ($selectedCustomers as $customer) {
            $amount = rand(100, 2000);
            GiftCard::create([
                'code' => strtoupper(Str::random(12)),
                'amount' => $amount,
                'balance' => $amount,
                'customer_id' => $customer->id,
                'recipient_name' => $customer->name,
                'recipient_email' => $customer->email,
                'message' => 'Happy shopping! Enjoy this gift card.',
                'expires_at' => Carbon::now()->addMonths(rand(6, 12)),
                'status' => 'active',
            ]);
        }
    }

    private function seedWishlistItems($products, $customers): void
    {
        $selectedCustomers = $customers->random(10);
        foreach ($selectedCustomers as $customer) {
            WishlistItem::create([
                'customer_id' => $customer->id,
                'product_id' => $products->random()->id,
            ]);
        }
    }

    private function seedBackInStockNotifications($products, $customers): void
    {
        $selectedProducts = $products->random(10);
        foreach ($selectedProducts as $product) {
            BackInStockNotification::create([
                'customer_id' => $customers->random()->id,
                'product_id' => $product->id,
                'product_variant_id' => null,
                'notified' => false,
            ]);
        }
    }

    private function seedRecentlyViewed($products, $customers): void
    {
        $selectedCustomers = $customers->random(10);
        foreach ($selectedCustomers as $customer) {
            foreach ($products->random(rand(3, 8)) as $product) {
                RecentlyViewedProduct::create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'ip_address' => '127.0.0.1',
                ]);
            }
        }
    }

    private function seedComparisons($products, $customers): void
    {
        $selectedCustomers = $customers->random(10);
        foreach ($selectedCustomers as $customer) {
            ProductComparison::create([
                'customer_id' => $customer->id,
                'session_id' => Str::random(40),
                'product_ids' => $products->random(rand(2, 3))->pluck('id')->toArray(),
            ]);
        }
    }

    private function seedReferrals($customers): void
    {
        $selectedCustomers = $customers->random(10);
        foreach ($selectedCustomers as $customer) {
            Referral::create([
                'customer_id' => $customer->id,
                'referral_code' => strtoupper(Str::random(8)),
                'referred_by' => $customers->random()->id,
                'used_count' => rand(0, 5),
                'max_uses' => 10,
                'expires_at' => Carbon::now()->addMonths(rand(1, 6)),
                'status' => 'active',
            ]);
        }
    }

    private function seedWallets($customers): void
    {
        foreach ($customers as $customer) {
            $wallet = CustomerWallet::create([
                'customer_id' => $customer->id,
                'balance' => rand(100, 5000),
            ]);

            foreach (range(1, rand(3, 8)) as $i) {
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => rand(0, 1) ? 'credit' : 'debit',
                    'amount' => rand(50, 500),
                    'reference_type' => null,
                    'reference_id' => null,
                    'note' => 'Transaction #' . $i,
                ]);
            }
        }
    }

    private function seedDiscounts($products): void
    {
        $discountTitles = [
            'Summer Sale', 'Winter Special', 'New Year Discount', 'Flash Sale',
            'Festival Offer', 'Clearance Sale', 'Member Exclusive', 'First Order Discount',
            'Bulk Purchase', 'Weekend Special'
        ];

        foreach ($discountTitles as $index => $title) {
            $applyOn = rand(0, 1) ? 'percentage' : 'fixed';
            $applyTo = rand(0, 1) ? 'all_products' : 'specific_product';
            $value = $applyOn === 'percentage' ? rand(5, 50) : rand(50, 500);
            $startDate = Carbon::now()->subDays(rand(1, 10));
            $endDate = Carbon::now()->addDays(rand(10, 60));

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
    }
}
