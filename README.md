# PHP_Larvel12_Simple_Ecommerce_Store
# Functionality for ecommerce store
```php
Create Client side and server side
Adding admin authentication for server side
Admin Profile section and Customer profile section available
Add to Cart and Existing Address selecting
Adding customer authentication for client side
Two option for place order in COD and ONlINE RAZORPAY
Server Side All customer order show page 
Customer side only for login customer order page
```


# Step 1 : Install Laravel12 and create project for simple ecommerce store
```php
composer create-project laravel/laravel PHP_Larvel12_Simple_Ecommerce_Store “^12.0”
```
# Step 2 :Setup database for .env file
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Your Database name
DB_USERNAME=root
DB_PASSWORD=
```

# Now Create Simple Ecommerce Store Project With Client and Server side .
# Step 3 :Create Products table and stored ProductName ,Details,Price,Size,Color,Category,Image
Run command for terminal
```php
php artisan make:model  Product-mc with controller and migration file
```
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'name',
    'details',
    'price',
    'image',
    'sizes',
    'colors',
    'categories',
     'status',
];

protected $casts = [
    'sizes' => 'array',
    'colors' => 'array',
    'categories' => 'array',
];
```
# Step 4 : Now adding create , edit , index , store and delete method for product controller
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ================================
    // LIST (ONLY ACTIVE PRODUCTS)
    // ================================
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::where('status', 'active') // ✅ IMPORTANT
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('details', 'like', "%{$search}%")
                      ->orWhere('price', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')   // OLD → NEW
            ->paginate(5)
            ->withQueryString();

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        return view('products.index', compact(
            'products',
            'sizes',
            'colors',
            'categories',
            'search'
        ));
    }

    // ================================
    // CREATE FORM
    // ================================
    public function create()
    {
        return view('products.create', [
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // ================================
    // STORE (DEFAULT STATUS = ACTIVE)
    // ================================
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'image'      => 'required|image',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Product::create([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'image'      => $imageName,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
            'status'     => 'active', // ✅ DEFAULT
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully');
    }

    // ================================
    // EDIT FORM
    // ================================
    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // ================================
    // UPDATE
    // ================================
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
            'image'      => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    // ================================
    // DELETE (SOFT DELETE BY STATUS)
    // ================================
    public function destroy(Product $product)
    {
        $product->update([
            'status' => 'deleted' // ✅ SOFT DELETE
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
```

# Step 5 : Create web route for routes/web.php file
```php
use App\Http\Controllers\ProductController;
 Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('prod
```
# Step 6 : Create Index,create,and edit .blade.file in resource/view/products folder
```php
resource/view/products/index.blade.php
resource/view/products/create.blade.php
resource/view/products/edit.blade.php
```
# Step 7 : Now Seam Create Sizes, Colors and Categories Module for Products 
# Create Size Controller , Table , Model , Routes and index , create and edit.blade.php file 
```php
Routes/web.php
use App\Http\Controllers\SizeController;
    Route::resource('sizes', SizeController::class);
```
# Controller Method
```php
<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // LIST
    public function index()
    {
        $sizes = Size::latest()->get();
        return view('sizes.index', compact('sizes'));
    }

    // CREATE FORM
    public function create()
    {
        return view('sizes.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name'
        ]);

        Size::create([
            'size_name' => $request->size_name
        ]);

        return redirect()->route('sizes.index')
                         ->with('success', 'Size added successfully');
    }

    // EDIT FORM
    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    // UPDATE
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name,' . $size->id
        ]);

        $size->update([
            'size_name' => $request->size_name
        ]);

        return redirect()->route('sizes.index')
                         ->with('success', 'Size updated successfully');
    }

    // DELETE
    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->route('sizes.index')
                         ->with('success', 'Size deleted successfully');
    }
}

```
```php
resource/view/sizes/index.blade.php
resource/view/ sizes /create.blade.php
resource/view/ sizes /edit.blade.php
```
# Create Color Controller , Table , Model , Routes and index , create and edit.blade.php file 
```php
Routes/web.php
use App\Http\Controllers\ColorController;
    Route::resource('colors', ColorController::class);
```
# Controller Method
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
class ColorController extends Controller
{
    public function index() {
        $colors = Color::latest()->get();
        return view('colors.index', compact('colors'));
    }

    public function create() {
        return view('colors.create');
    }

    public function store(Request $request) {
        $request->validate([
            'color_name' => 'required|unique:colors'
        ]);

        Color::create($request->only('color_name'));

        return redirect()->route('colors.index')->with('success','Color added');
    }

    public function edit(Color $color) {
        return view('colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color) {
        $request->validate([
            'color_name' => 'required|unique:colors,color_name,'.$color->id
        ]);

        $color->update($request->only('color_name'));

        return redirect()->route('colors.index')->with('success','Color updated');
    }

    public function destroy(Color $color) {
        $color->delete();
        return redirect()->route('colors.index')->with('success','Color deleted');
    }
}

```
```php
resource/view/colors/index.blade.php
resource/view/ colors /create.blade.php
resource/view/ colors /edit.blade.php
```
# Create Categories Controller , Table , Model , Routes and index , create and edit.blade.php file 
```php
Routes/web.php
use App\Http\Controllers\CategoriesController;
    Route::resource('category', CategoriesController::class);
```
# Controller Method
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'category_name' => 'required|unique:categories'
        ]);

        Category::create($request->only('category_name'));

        return redirect()->route('categories.index')->with('success','Category added');
    }

    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name,'.$category->id
        ]);

        $category->update($request->only('category_name'));

        return redirect()->route('categories.index')->with('success','Category updated');
    }

    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted');
    }
}
```
```php
resource/view/categories/index.blade.php
resource/view/ categories /create.blade.php
resource/view/ categories /edit.blade.php
```
# Now Adding Admin authentication For brezz laravel
```php
composer require laravel/breeze --dev
```
```php
php artisan breeze:install blade
```
```php
npm install
npm run dev
```
```php
php artisan migrate
```
```php
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```
```php
php artisan serve
Then login → redirect to:
/products
```
# Now Adding Admin layout for layouts folder

# Resource/view/layouts/admin.blade.php

<img width="700" height="500" alt="image" src="https://github.com/user-attachments/assets/00a165e9-ebcf-4e43-8812-47467421737d" />


<img width="700" height="500" alt="image" src="https://github.com/user-attachments/assets/03c60c53-b8f1-4f3f-800a-db8589f8b209" />


 <img width="628" height="300" alt="image" src="https://github.com/user-attachments/assets/bef0a23f-acdc-4a80-8ff6-dacdb903893f" />

 


# Now This All Products Show Client side and adding add to cart function this products:
Create Route , controller for customer
# Controller method
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // CUSTOMER PRODUCT LIST + SEARCH + PAGINATION
public function index(Request $request)
{
    $search = $request->search;

    // 🔹 NAME se ID nikalna
    $sizeIds = Size::where('size_name', 'like', "%{$search}%")
                    ->pluck('id')
                    ->toArray();

    $colorIds = Color::where('color_name', 'like', "%{$search}%")
                     ->pluck('id')
                     ->toArray();

    $categoryIds = Category::where('category_name', 'like', "%{$search}%")
                            ->pluck('id')
                            ->toArray();

    // 🔹 PRODUCT SEARCH
     $products = Product::where('status', 'active')
        ->when($search, function ($query) use (
            $search,
            $sizeIds,
            $colorIds,
            $categoryIds
        ) {
            $query->where(function ($q) use (
                $search,
                $sizeIds,
                $colorIds,
                $categoryIds
            ) {

                // ✅ Product fields
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");

                // ✅ Size NAME search
                foreach ($sizeIds as $id) {
                    $q->orWhereJsonContains('sizes', $id);
                }

                // ✅ Color NAME search
                foreach ($colorIds as $id) {
                    $q->orWhereJsonContains('colors', $id);
                }

                // ✅ Category NAME search
                foreach ($categoryIds as $id) {
                    $q->orWhereJsonContains('categories', $id);
                }
            });
        })
        ->orderBy('id', 'asc')   // ✅ OLD → NEW (new record niche)
        ->paginate(8)
        ->withQueryString();

    // 🔹 Mapping for view
    $sizes = Size::pluck('size_name', 'id');
    $colors = Color::pluck('color_name', 'id');
    $categories = Category::pluck('category_name', 'id');

    // 🔹 CUSTOMER AUTH INFO
    $isCustomerLoggedIn = auth('customer')->check();
    $customer = auth('customer')->user();

    return view('customer.index', compact(
        'products',
        'sizes',
        'colors',
        'categories',
        'isCustomerLoggedIn',
        'customer'
    ));
}

}
```
```php
Route for web.php file
use App\Http\Controllers\CustomerController;

Route::get('/customer/products', [CustomerController::class, 'index'])
    ->name('customer.products');
```

# Create index.blade.php file for customers folder in resource/view/customers folder

# Resource/view/customers/index.blade.php
# Now Create Carts table , model , controller and index.blade.php file and adding  web route and store  add to carts products 
```php
Create carts table , model , controller and web route
Controller
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 🛒 Cart List (ONLY LOGGED-IN CUSTOMER)
    public function index()
    {
        $customerId = auth('customer')->id();

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        return view('cart.index', compact(
            'cartItems',
            'sizes',
            'colors',
            'categories'
        ));
    }

    // ➕ Add to Cart (SAVE customer_id)
   public function store(Request $request)
{
    $request->validate([
        'product_id'  => 'required|exists:products,id',
        'size_id'     => 'required',
        'color_id'    => 'required',
        'category_id' => 'required',
        'quantity'    => 'required|integer|min:1|max:5',
    ]);

    $customerId = auth('customer')->id();

    $product = Product::findOrFail($request->product_id);

    // 🔍 Check if same item already exists
    $cartItem = Cart::where('customer_id', $customerId)
        ->where('product_id', $product->id)
        ->where('size_id', $request->size_id)
        ->where('color_id', $request->color_id)
        ->first();

    if ($cartItem) {
        // ➕ increase quantity (max 5)
        $cartItem->quantity = min($cartItem->quantity + $request->quantity, 5);
        $cartItem->save();
    } else {
        Cart::create([
            'customer_id' => $customerId,
            'product_id'  => $product->id,
            'size_id'     => $request->size_id,
            'color_id'    => $request->color_id,
            'category_id' => $request->category_id,
            'quantity'    => $request->quantity,
            'price'       => $product->price,
        ]);
    }

    return redirect()->route('cart.index')
        ->with('success', 'Product added to cart successfully');
}

    // ❌ Remove (ONLY OWN CART ITEM)
    public function destroy(Cart $cart)
    {
        // 🔐 Security check
        if ($cart->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Item removed from cart');
    }
    // 🔄 UPDATE QUANTITY (+ / -)
public function updateQuantity(Request $request, Cart $cart)
{
    // 🔐 Security
    if ($cart->customer_id !== auth('customer')->id()) {
        abort(403);
    }

    $request->validate([
        'action' => 'required|in:increase,decrease',
    ]);

    if ($request->action === 'increase' && $cart->quantity < 5) {
        $cart->quantity += 1;
    }

    if ($request->action === 'decrease' && $cart->quantity > 1) {
        $cart->quantity -= 1;
    }

    $cart->save();

    return redirect()->route('cart.index');
}

}
```
```php
Web route for routes/web.php file
use App\Http\Controllers\CartController;

 Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add', [CartController::class, 'store'])
        ->name('cart.add');

    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])
        ->name('cart.remove');
Route::post('/cart/update-quantity/{cart}',
    [CartController::class, 'updateQuantity']
)->name('cart.update.quantity');
```
# Resource/view/cart/index.blade.php

#  Now Create customer authentication and set up for client side for when customer  any product add to cart if not logged for any time then open customer login page and after success customer login to add to cart any products 
Create customer table , route , customerauthcontroller,and register,login and dashboard .blade.php file
# CustomerAuthController method
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    // Register page
    public function register()
    {
        return view('customer.register');
    }

    // Register store
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')
            ->with('success', 'Register successful');
    }

    // Login page
    public function login()
    {
        return view('customer.login');
    }

    // ✅ LOGIN POST (UPDATED)
    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {

            //  Login ke baad DIRECT products page
            return redirect()->route('customer.products');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ]);
    }

    // (Optional) Dashboard – ab use nahi hoga
    public function dashboard()
    {
        return view('customer.dashboard');
    }

    // Logout
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login');
    }
}
```
```php
Web route in web.php file
use App\Http\Controllers\CustomerAuthController;
/ Register
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

 ```
<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/5c4d3d02-e79b-4bce-825e-27e75ac51a18" />

<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/64ef6d93-76bf-4a25-a93c-bb8479219e17" />

<img width="628" height="322" alt="image" src="https://github.com/user-attachments/assets/a34d4463-0ece-42de-9e5d-06f6a83fcfcd" />
<img width="628" height="500" alt="image" src="https://github.com/user-attachments/assets/c3598cc5-f96d-423b-87b2-687ede5c1b33" />

 


 

 

# When Click process to checkout then open Address Page and store all address details 
# Create address table, controller , web.php route , create blade.php file
# AddressController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // ==============================
    // 📍 Address list + form
    // ==============================
    public function index()
    {
        $customerId = auth('customer')->id();

        // ✅ ONLY LOGGED-IN CUSTOMER ADDRESSES
        $addresses = Address::where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('address.index', compact('addresses'));
    }

    // ==============================
    // 💾 Save address (NORMAL SAVE PAGE)
    // ==============================
    public function store(Request $request)
    {
        $customerId = auth('customer')->id();

        $request->validate([
            'address' => 'required',
            'city'    => 'required',
            'state'   => 'required',
            'pincode' => 'required|min:6',
        ]);

        // 🔐 DUPLICATE PROTECTION
        Address::firstOrCreate(
            [
                'customer_id' => $customerId,
                'address'     => $request->address,
                'nearby'      => $request->nearby,
                'city'        => $request->city,
                'state'       => $request->state,
                'pincode'     => $request->pincode,
            ]
        );

        return redirect()->route('address.index')
            ->with('success', 'Address saved successfully');
    }

    // ==============================
    // 🚚 SAVE ADDRESS FOR CHECKOUT
    // ==============================
    public function saveForCheckout(Request $request)
    {
        $customerId = auth('customer')->id();

        /*
        |--------------------------------------------------
        | ✅ CASE 1: EXISTING ADDRESS SELECTED (DROPDOWN)
        | 👉 DB me bilkul save NA ho
        |--------------------------------------------------
        */
        if (!empty($request->address_id)) {

            $address = Address::where('id', $request->address_id)
                ->where('customer_id', $customerId)
                ->firstOrFail();

            session([
                'checkout_address' => [
                    'address' => $address->address,
                    'nearby'  => $address->nearby,
                    'city'    => $address->city,
                    'state'   => $address->state,
                    'pincode' => $address->pincode,
                ]
            ]);

            return redirect()->route('checkout.payment');
        }

        /*
        |--------------------------------------------------
        | ✅ CASE 2: NEW ADDRESS (CHECK DUPLICATE)
        |--------------------------------------------------
        */
        $request->validate([
            'address' => 'required',
            'city'    => 'required',
            'state'   => 'required',
            'pincode' => 'required|min:6',
        ]);

        // 🔥 MAIN FIX: SAME ADDRESS → SAME RECORD
        $address = Address::firstOrCreate(
            [
                'customer_id' => $customerId,
                'address'     => $request->address,
                'nearby'      => $request->nearby,
                'city'        => $request->city,
                'state'       => $request->state,
                'pincode'     => $request->pincode,
            ]
        );

        session([
            'checkout_address' => [
                'address' => $address->address,
                'nearby'  => $address->nearby,
                'city'    => $address->city,
                'state'   => $address->state,
                'pincode' => $address->pincode,
            ]
        ]);

        return redirect()->route('checkout.payment');
    }
}
```
```php
Web.php route
use App\Http\Controllers\AddressController;
 // Address
    Route::get('/checkout/address', [AddressController::class, 'index'])
        ->name('address.index');

    Route::post('/checkout/address', [AddressController::class, 'saveForCheckout'])
        ->name('checkout.saveAddress');
```
# Create index.blade.php file for resource/view/address folder
# Resource/view/address/index.blade.php file

 <img width="500" height="300" alt="image" src="https://github.com/user-attachments/assets/a0c3d859-560e-47c8-9e8f-c19c0d4a7d30" />

# When fillup all address details and click save/continue payment then open payment page and show all details for customer name , address , products details.
# Create checkout controller 
```php
class CheckoutController extends Controller
{
    // ==============================
    // ✅ PAYMENT PAGE (GET)
    // ==============================
    public function paymentPage()
    {
        $customerId = auth('customer')->id();
        $address = session('checkout_address');

        if (!$address) {
            return redirect()->route('customer.products')
                ->with('error', 'Invalid checkout session');
        }

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.products')
                ->with('error', 'Cart is empty');
        }

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        // 🔥 DISCOUNT LOGIC
        $cartProductIds = $cartItems->pluck('product_id')->toArray();
        $today = Carbon::today();

        $allProductDiscounts = Discount::where('apply_to', 'all_products')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        $specificDiscounts = Discount::where('apply_to', 'specific_product')
            ->get()
            ->filter(function ($discount) use ($cartProductIds) {
                $ids = json_decode($discount->product_ids, true) ?? [];
                return count(array_intersect($ids, $cartProductIds)) > 0;
            });

        return view('checkout.payment', compact(
            'address',
            'cartItems',
            'sizes',
            'colors',
            'categories',
            'allProductDiscounts',
            'specificDiscounts'
        ));
    }
```
```php
Create web.php file
use App\Http\Controllers\CheckoutController;
 Route::get('/checkout/payment', [CheckoutController::class, 'paymentPage'])
        ->name('checkout.payment');
```
# Create payment .blade.php file for resource/view/checkout folder

<img width="600" height="300" alt="image" src="https://github.com/user-attachments/assets/3cffd0e4-1664-4124-a292-11460ed6d686" />

# Now Adding payment method for cash and razorpay and when click any one option then order place successful and store all details for orders and order_items table in database 
# Adding razorpay id in .env file
```php
RAZORPAY_KEY=Your_key
RAZORPAY_SECRET=Your_secret
```
# Create orders and order_items table and model 
# Orders model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'subtotal',          // ✅ NEW
        'discount_amount',   // ✅ NEW
        'total_price',
        'payment_method',
        'payment_status',    // (ONLINE / COD)
        'status',            // (pending, shipped, delivered etc.)
    ];

    // 🔗 Order → Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 🔗 Order → Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🔗 Order → Address
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
```

# orderitem model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'category_id',
        'quantity',
        'price',
        'discount_amount', // ✅ NEW
        'total',
    ];

    // 🔗 Item → Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔗 Item → Size
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // 🔗 Item → Color
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // 🔗 Item → Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```
# Adding Web Route For Order place in Cash and Razorpay:
```php
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

```
# Adding Cash and Online Razorpay Method In Place Order and Store Method For ChekoutController
# ChekoutController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Address;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Discount;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // ==============================
    // ✅ PAYMENT PAGE (GET)
    // ==============================
    public function paymentPage()
    {
        $customerId = auth('customer')->id();
        $address = session('checkout_address');

        if (!$address) {
            return redirect()->route('customer.products')
                ->with('error', 'Invalid checkout session');
        }

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.products')
                ->with('error', 'Cart is empty');
        }

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        // 🔥 DISCOUNT LOGIC
        $cartProductIds = $cartItems->pluck('product_id')->toArray();
        $today = Carbon::today();

        $allProductDiscounts = Discount::where('apply_to', 'all_products')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        $specificDiscounts = Discount::where('apply_to', 'specific_product')
            ->get()
            ->filter(function ($discount) use ($cartProductIds) {
                $ids = json_decode($discount->product_ids, true) ?? [];
                return count(array_intersect($ids, $cartProductIds)) > 0;
            });

        return view('checkout.payment', compact(
            'address',
            'cartItems',
            'sizes',
            'colors',
            'categories',
            'allProductDiscounts',
            'specificDiscounts'
        ));
    }

    // ==============================
    // ✅ RAZORPAY ORDER CREATE
    // ==============================
    public function razorpayOrder(Request $request)
    {
        $amount = (float) $request->amount;

        if ($amount <= 0) {
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $order = $api->order->create([
            'receipt'  => 'order_' . time(),
            'amount'   => $amount * 100,
            'currency' => 'INR'
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount'   => $amount,
            'key'      => config('services.razorpay.key')
        ]);
    }

    // ==============================
    // ✅ RAZORPAY VERIFY
    // ==============================
    public function razorpayVerify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature'  => 'required',
        ]);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            $request->merge(['payment_method' => 'ONLINE']);
            return $this->placeOrder($request);

        } catch (\Exception $e) {
            return redirect()->route('checkout.payment')
                ->with('error', 'Payment verification failed');
        }
    }

    // ==============================
    // ✅ PLACE ORDER (COD + ONLINE)
    // ==============================
    public function placeOrder(Request $request)
    {
        $customerId = auth('customer')->id();

        $request->validate([
            'payment_method' => 'required|in:COD,ONLINE',
        ]);

        $discount = (float) ($request->discount ?? 0);

        $sessionAddress = session('checkout_address');
        if (!$sessionAddress) {
            return redirect()->route('address.index')
                ->with('error', 'Please select address again');
        }

        $address = Address::where('customer_id', $customerId)
            ->where('address', $sessionAddress['address'])
            ->where('city', $sessionAddress['city'])
            ->where('state', $sessionAddress['state'])
            ->where('pincode', $sessionAddress['pincode'])
            ->firstOrFail();

        $cartItems = Cart::where('customer_id', $customerId)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.products')
                ->with('error', 'Cart is empty');
        }

        // 🔢 TOTALS
        $subtotal = $cartItems->sum(fn ($i) => $i->price * $i->quantity);
        $finalTotal = max($subtotal - $discount, 0);

        // 🧾 CREATE ORDER
        $order = Order::create([
            'customer_id'     => $customerId,
            'address_id'      => $address->id,
            'subtotal'        => $subtotal,
            'discount_amount' => $discount,
            'total_price'     => $finalTotal,
            'payment_method'  => $request->payment_method,
             'status'          => $request->payment_method === 'ONLINE'
                            ? 'paid'
                            : 'pending',
        ]);

        // 🧾 ORDER ITEMS (PROPORTIONAL DISCOUNT)
        foreach ($cartItems as $item) {

            $itemSubtotal = $item->price * $item->quantity;

            $itemDiscount = $subtotal > 0
                ? round(($itemSubtotal / $subtotal) * $discount, 2)
                : 0;

            OrderItem::create([
                'order_id'        => $order->id,
                'product_id'      => $item->product_id,
                'size_id'         => $item->size_id,
                'color_id'        => $item->color_id,
                'category_id'     => $item->category_id,
                'quantity'        => $item->quantity,
                'price'           => $item->price,
                'discount_amount' => $itemDiscount,
                'total'           => $itemSubtotal - $itemDiscount,
            ]);
        }

        // 🧹 CLEAR
        Cart::where('customer_id', $customerId)->delete();
        session()->forget('checkout_address');

        return redirect()->route('order.success');
    }
}
```
# Create Success.blade.php file in resource/view/checkout folder
```php
@extends('layouts.customer')

@section('content')

<div class="text-center mt-5">
    <h2 class="text-success">🎉 Order Placed Successfully!</h2>
    <p class="mt-3">Thank you for shopping with us.</p>

    <a href="{{ route('customer.products') }}" class="btn btn-primary mt-3">
        Continue Shopping
    </a>
</div>

@endsection
```
# Now Create Customer Layouts for Header and Footer Function
# resource/view/layouts/customer.blade.php

# resource/view/layouts/navigation.blade.php

# resource/view/layouts/app.blade.php


# Create profile.blade.php file in resource/view/customer folder

<img width="600" height="300" alt="image" src="https://github.com/user-attachments/assets/e0d1ef44-4986-423d-9b0b-c0b00ca15f03" />

 
# Create aboutus , privacy and terms condition page and this page link adding footer function
# Create Pagecontroller , web route and blade file
# Controller
```php
<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}
```
```php
Web.php route
use App\Http\Controllers\PageController;
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-conditions', [PageController::class, 'terms'])->name('terms');
```
# Create blade.php file for resource/view/pages folder
```php
resource/view/pages/about.blade.php
resource/view/pages/privacy.blade.php
resource/view/pages/terms.blade.php
```
# Create all customer order page in server side.
```php
Create web.route 
use App\Http\Controllers\AdminOrderController;
Route::middleware('auth:customer')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');
});

Route::post('/admin/orders/{order}/status', 
    [AdminOrderController::class, 'updateStatus']
)->name('admin.orders.status');

Route::get('/admin/orders', [AdminOrderController::class, 'index'])
    ->name('admin.orders.index');
Route::middleware('auth:customer')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');
});
```
# Create admincontroller 
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // ================================
    // ALL ORDERS (ADMIN)
    // ================================
 public function index(Request $request)
{
    $search = $request->search;
    $priceSort = $request->price_sort;
     $status     = $request->status; 

    $orders = Order::with([
        'items.product',
        'items.size',
        'items.color',
        'items.category',
        'address',
        'customer',
    ])
    ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {

            // 🔹 Order ID
            $q->where('id', 'like', "%{$search}%")

            // 🔹 Total price (DIRECT COLUMN)
            ->orWhere('total_price', 'like', "%{$search}%")

            // 🔹 Customer name
            ->orWhereHas('customer', function ($cq) use ($search) {
                $cq->where('name', 'like', "%{$search}%");
            })

            // 🔹 Address
            ->orWhereHas('address', function ($aq) use ($search) {
                $aq->where('address', 'like', "%{$search}%")
                   ->orWhere('city', 'like', "%{$search}%")
                   ->orWhere('state', 'like', "%{$search}%")
                   ->orWhere('pincode', 'like', "%{$search}%");
            })

            // 🔹 Product
            ->orWhereHas('items.product', function ($pq) use ($search) {
                $pq->where('name', 'like', "%{$search}%");
            })
             // 🔹 Order Date (created_at)
            ->orWhereDate('created_at', $search)
            ->orWhere('created_at', 'like', "%{$search}%")

            // 🔹 Payment type smart search
->orWhere(function ($pq) use ($search) {
    $keyword = strtolower($search);

    if (in_array($keyword, ['cash', 'cod', 'offline'])) {
        $pq->whereIn('payment_method', ['COD', 'Cash', 'CASH']);
    } elseif (in_array($keyword, ['online', 'paid'])) {
        $pq->whereIn('payment_method', ['ONLINE', 'Online']);
    } else {
        $pq->where('payment_method', 'like', "%{$search}%");
    }
})

            // 🔹 Size
            ->orWhereHas('items.size', function ($sq) use ($search) {
                $sq->where('size_name', 'like', "%{$search}%");
            })

            // 🔹 Color
            ->orWhereHas('items.color', function ($cq) use ($search) {
                $cq->where('color_name', 'like', "%{$search}%");
            })

            // 🔹 Category
            ->orWhereHas('items.category', function ($catq) use ($search) {
                $catq->where('category_name', 'like', "%{$search}%");
            });
        });
    })

      // 🟡 STATUS FILTER
    ->when($status, function ($query) use ($status) {
        $query->where('status', $status);
    })

    // 💰 PRICE SORT
    ->when($priceSort, function ($query) use ($priceSort) {
        if ($priceSort === 'high') {
            $query->orderBy('total_price', 'desc');
        } elseif ($priceSort === 'low') {
            $query->orderBy('total_price', 'asc');
        }
    }, function ($query) {
        $query->orderBy('created_at', 'desc');
    })

    ->paginate(10)
    ->withQueryString();

    return view('admin.orders.index', compact('orders', 'search', 'priceSort', 'status'));
}

public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,on_the_way,shipped,delivered',
    ]);

    $order->update([
        'status' => $request->status,
    ]);

    return back()->with('success', 'Order status updated');
}

}
```


# Create order blade.php file in resource/view/admin/order folder

<img width="979" height="347" alt="image" src="https://github.com/user-attachments/assets/f7c89a07-0e60-4849-ab4c-b77b35ac1c0d" />

 
# Create  Client side Login Customer Myorder page and show all order details .
# Create web route , controller and order index file
```php
Route
use App\Http\Controllers\CustomerOrderController;
Route::middleware('auth:customer')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');
});
```

# Controller for customer order
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $customerId = auth('customer')->id();

        $search    = $request->search;
        $priceSort = $request->price_sort; // high | low
        $status    = $request->status;     // pending | on_the_way | shipped | delivered

        $orders = Order::with([
                'items.product',
                'items.size',
                'items.color',
                'items.category',
                'address'
            ])
            ->where('customer_id', $customerId)

            //  SEARCH (ALL)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('total_price', 'like', "%{$search}%")
                      ->orWhereDate('created_at', $search)
                      ->orWhere('created_at', 'like', "%{$search}%")

                      // PRODUCT
                      ->orWhereHas('items.product', function ($pq) use ($search) {
                          $pq->where('name', 'like', "%{$search}%");
                      })

                      // SIZE
                      ->orWhereHas('items.size', function ($sq) use ($search) {
                          $sq->where('size_name', 'like', "%{$search}%");
                      })

                      // COLOR
                      ->orWhereHas('items.color', function ($cq) use ($search) {
                          $cq->where('color_name', 'like', "%{$search}%");
                      })

                      // CATEGORY
                      ->orWhereHas('items.category', function ($catq) use ($search) {
                          $catq->where('category_name', 'like', "%{$search}%");
                      });
                });
            })

            //  STATUS FILTER
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })

            //  PRICE SORT
            ->when($priceSort, function ($query) use ($priceSort) {
                if ($priceSort === 'high') {
                    $query->orderBy('total_price', 'desc');
                } elseif ($priceSort === 'low') {
                    $query->orderBy('total_price', 'asc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })

            ->paginate(10)
            ->withQueryString();

        return view('customer.orders.index', compact(
            'orders',
            'search',
            'priceSort',
            'status'
        ));
    }
}

```

# Create index.blade.php file in resource/view/customer/order folder

<img width="628" height="234" alt="image" src="https://github.com/user-attachments/assets/6e0389b7-602d-4185-a47a-e642dc320201" />

# Now Your Ecommerce Store is ready to use for all function is easy to work all time :
 <img width="1504" height="879" alt="image" src="https://github.com/user-attachments/assets/90d06630-b415-4213-8bdf-e3554fd3d5a7" />
<img width="1038" height="900" alt="image" src="https://github.com/user-attachments/assets/74a27eed-75c6-4e5e-b924-7527d6b3407e" />


