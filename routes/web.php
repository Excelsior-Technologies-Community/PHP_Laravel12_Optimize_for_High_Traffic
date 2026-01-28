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

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});



Route::resource('discounts', \App\Http\Controllers\DiscountController::class);



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
