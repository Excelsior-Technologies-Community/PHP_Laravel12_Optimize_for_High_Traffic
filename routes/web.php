<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProductComparisonController;
use App\Http\Controllers\CustomerWalletController;
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\CustomerGiftCardController;
use App\Http\Controllers\CustomerDiscountController;
use App\Http\Controllers\BackInStockNotificationController;
use App\Http\Controllers\CustomerSizeGuideController;
use App\Http\Controllers\ProductRecommendationController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\SizeGuideController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminReturnController;
use App\Http\Controllers\AdminWalletController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\BannerController;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});



Route::middleware('auth:customer')->group(function () {
    Route::get('/my-profile', [CustomerProfileController::class, 'index'])
        ->name('customer.profile');

    Route::post('/my-profile', [CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
});




Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-conditions', [PageController::class, 'terms'])->name('terms');


Route::middleware('auth:customer')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');
});


Route::post('/admin/orders/{order}/status', 
    [AdminOrderController::class, 'updateStatus']
)->name('admin.orders.status');


Route::get('/admin/orders', [AdminOrderController::class, 'index'])
    ->name('admin.orders.index');

/*
|--------------------------------------------------------------------------
| CUSTOMER AUTH
|--------------------------------------------------------------------------
*/

// Register
Route::get('/customer/register', [CustomerAuthController::class, 'register'])
    ->name('customer.register');

Route::post('/customer/register', [CustomerAuthController::class, 'registerPost'])
    ->name('customer.register.post');

// Login
Route::get('/customer/login', [CustomerAuthController::class, 'login'])
    ->name('customer.login');

Route::post('/customer/login', [CustomerAuthController::class, 'loginPost'])
    ->name('customer.login.post');

// Logout (protected)
Route::middleware('auth:customer')->group(function () {
    Route::get('/customer/logout', [CustomerAuthController::class, 'logout'])
        ->name('customer.logout');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER PRODUCTS (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/customer/products', [CustomerController::class, 'index'])
    ->name('customer.products');
/*
|--------------------------------------------------------------------------
| AUTO LOGOUT ON TAB / WINDOW CLOSE (CUSTOMER)
|--------------------------------------------------------------------------
*/
Route::post('/customer/auto-logout', function () {
    if (auth('customer')->check()) {
       Auth::guard('customer')->logout();
        session()->invalidate();
        session()->regenerateToken();
    }
    return response()->noContent();
})->name('customer.auto.logout');

/*
|--------------------------------------------------------------------------
| CART (CUSTOMER ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add', [CartController::class, 'store'])
        ->name('cart.add');

    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])
        ->name('cart.remove');
});
Route::post('/cart/update-quantity/{cart}',
    [CartController::class, 'updateQuantity']
)->name('cart.update.quantity');

/*
|--------------------------------------------------------------------------
| WISHLIST (CUSTOMER ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'store'])->name('wishlist.add');
Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
Route::delete('/wishlist/product/{product}', [WishlistController::class, 'destroyByProduct'])->name('wishlist.remove.by.product');
});

/*
|--------------------------------------------------------------------------
| PRODUCT REVIEWS (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::post('/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');
    Route::get('/products/{product}/reviews', [ProductReviewController::class, 'index'])->name('reviews.index');
});

/*
|--------------------------------------------------------------------------
| PRODUCT COMPARISON (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/compare', [ProductComparisonController::class, 'show'])->name('compare.show');
    Route::post('/compare/add', [ProductComparisonController::class, 'add'])->name('compare.add');
    Route::post('/compare/remove', [ProductComparisonController::class, 'remove'])->name('compare.remove');
    Route::post('/compare/clear', [ProductComparisonController::class, 'clear'])->name('compare.clear');
});

/*
|--------------------------------------------------------------------------
| RECENTLY VIEWED (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::post('/products/{product}/viewed', [ProductRecommendationController::class, 'trackView'])
    ->name('products.viewed');

/*
|--------------------------------------------------------------------------
| SIZE GUIDE (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/products/{product}/size-guide', [CustomerSizeGuideController::class, 'show'])
    ->name('products.size-guide');

/*
|--------------------------------------------------------------------------
| RETURNS (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/returns', [ReturnRequestController::class, 'index'])->name('returns.index');
    Route::post('/returns', [ReturnRequestController::class, 'store'])->name('returns.store');
});

/*
|--------------------------------------------------------------------------
| WALLET (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/wallet', [CustomerWalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/recharge', [CustomerWalletController::class, 'recharge'])->name('wallet.recharge');
    Route::get('/wallet/transactions', [CustomerWalletController::class, 'transactions'])->name('wallet.transactions');
});

/*
|--------------------------------------------------------------------------
| REFERRAL (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/referral', [ReferralController::class, 'index'])->name('referral.index');
    Route::post('/referral/generate-code', [ReferralController::class, 'generateCode'])->name('referral.generate');
    Route::post('/referral/apply', [ReferralController::class, 'apply'])->name('referral.apply');
});

/*
|--------------------------------------------------------------------------
| GIFT CARDS (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/gift-cards', [CustomerGiftCardController::class, 'index'])->name('gift-cards.index');
    Route::get('/gift-cards/purchase', [CustomerGiftCardController::class, 'purchaseForm'])->name('gift-cards.purchase.form');
    Route::post('/gift-cards/purchase', [CustomerGiftCardController::class, 'purchase'])->name('gift-cards.purchase');
    Route::get('/gift-cards/redeem', [CustomerGiftCardController::class, 'redeemForm'])->name('gift-cards.redeem.form');
    Route::post('/gift-cards/redeem', [CustomerGiftCardController::class, 'redeem'])->name('gift-cards.redeem');
});

/*
|--------------------------------------------------------------------------
| DISCOUNTS (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/discounts', [CustomerDiscountController::class, 'index'])->name('customer.discounts.index');
    Route::get('/discounts/{discount}', [CustomerDiscountController::class, 'show'])->name('customer.discounts.show');
});

/*
|--------------------------------------------------------------------------
| BACK IN STOCK NOTIFICATIONS (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::post('/back-in-stock', [BackInStockNotificationController::class, 'store'])->name('back-in-stock.store');
    Route::delete('/back-in-stock/{notification}', [BackInStockNotificationController::class, 'destroy'])->name('back-in-stock.destroy');
});

/*
|--------------------------------------------------------------------------
| RECOMMENDATIONS (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/products/{product}/recommendations', [ProductRecommendationController::class, 'related'])
    ->name('products.recommendations');


/*
|--------------------------------------------------------------------------
| CHECKOUT FLOW (CUSTOMER ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {

    // Address
    Route::get('/checkout/address', [AddressController::class, 'index'])
        ->name('address.index');

    Route::post('/checkout/address', [AddressController::class, 'saveForCheckout'])
        ->name('checkout.saveAddress');

    // Payment
    Route::get('/checkout/payment', [CheckoutController::class, 'paymentPage'])
        ->name('checkout.payment');

    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('place.order');

    // Razorpay
    Route::post('/razorpay/order', [CheckoutController::class, 'razorpayOrder'])
        ->name('razorpay.order');

    Route::post('/razorpay/verify', [CheckoutController::class, 'razorpayVerify'])
        ->name('razorpay.verify');

    // Success
    Route::get('/order-success', function () {
        return view('checkout.success');
    })->name('order.success');
});

/*
|--------------------------------------------------------------------------
| ADMIN / BACKEND (DEFAULT AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // PRODUCTS
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // CATEGORIES
    Route::resource('categories', CategoryController::class);

    // COLORS
    Route::resource('colors', ColorController::class);

    // SIZES
    Route::get('/sizes', [SizeController::class, 'index'])->name('sizes.index');
    Route::get('/sizes/create', [SizeController::class, 'create'])->name('sizes.create');
    Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store');
    Route::get('/sizes/{size}/edit', [SizeController::class, 'edit'])->name('sizes.edit');
    Route::put('/sizes/{size}', [SizeController::class, 'update'])->name('sizes.update');
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');

    // BRANDS
    Route::resource('brands', BrandController::class);

    // PRODUCT TAGS
    Route::resource('product-tags', ProductTagController::class);

    // PRODUCT VARIANTS
    Route::resource('product-variants', ProductVariantController::class);

    // FLASH SALES
    Route::resource('flash-sales', FlashSaleController::class);
    Route::post('/flash-sales/{flashSale}/toggle', [FlashSaleController::class, 'toggleStatus'])->name('flash-sales.toggle');

    // SIZE GUIDES
    Route::resource('size-guides', SizeGuideController::class);

    // REVIEWS
    Route::get('/admin/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/admin/reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('admin.reviews.status');

    // RETURNS
    Route::get('/admin/returns', [AdminReturnController::class, 'index'])->name('admin.returns.index');
    Route::post('/admin/returns/{returnRequest}/status', [AdminReturnController::class, 'updateStatus'])->name('admin.returns.status');

    // WALLETS
    Route::get('/admin/wallets', [AdminWalletController::class, 'index'])->name('admin.wallets.index');
    Route::post('/admin/wallets/{wallet}/recharge', [AdminWalletController::class, 'recharge'])->name('admin.wallets.recharge');
    Route::get('/admin/wallets/transactions', [AdminWalletController::class, 'transactions'])->name('admin.wallets.transactions');

    // GIFT CARDS
    Route::resource('admin/gift-cards', GiftCardController::class)->names('admin.gift-cards');

    // DISCOUNTS
    Route::resource('discounts', \App\Http\Controllers\DiscountController::class);

    // BANNERS
    Route::resource('banners', BannerController::class);

    // IMPORT / EXPORT
    Route::get('/admin/import-export', [ImportExportController::class, 'importForm'])->name('import-export.form');
    Route::post('/admin/import', [ImportExportController::class, 'import'])->name('import.execute');
    Route::get('/admin/export', [ImportExportController::class, 'export'])->name('export.execute');
});



Route::get('/register', function () {
    return redirect()->route('login');
});

Route::post('/register', function () {
    return redirect()->route('login');
});
/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';
