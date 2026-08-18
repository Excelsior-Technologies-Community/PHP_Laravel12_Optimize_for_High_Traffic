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
use Illuminate\Support\Facades\Cache;
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

        $sizes      = Cache::remember('filters.sizes', 3600, fn () => Size::pluck('size_name', 'id'));
        $colors     = Cache::remember('filters.colors', 3600, fn () => Color::pluck('color_name', 'id'));
        $categories = Cache::remember('filters.categories', 3600, fn () => Category::pluck('category_name', 'id'));

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
                $ids = $discount->product_ids ?? [];
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
            ->where('full_name', $sessionAddress['full_name'])
            ->where('mobile', $sessionAddress['mobile'])
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
