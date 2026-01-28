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
        $search     = $request->search;
        $priceSort = $request->price_sort;
        $status    = $request->status;
        $date      = $request->date; // ✅ CALENDAR DATE

        $orders = Order::with([
            'items.product',
            'items.size',
            'items.color',
            'items.category',
            'address',
            'customer',
        ])

        // 🔍 SEARCH
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {

                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('total_price', 'like', "%{$search}%")

                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })

                  ->orWhereHas('address', function ($aq) use ($search) {
                      $aq->where('address', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%")
                         ->orWhere('state', 'like', "%{$search}%")
                         ->orWhere('pincode', 'like', "%{$search}%");
                  })

                  ->orWhereHas('items.product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  })

                  ->orWhereDate('created_at', $search)
                  ->orWhere('created_at', 'like', "%{$search}%")

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

                  ->orWhereHas('items.size', function ($sq) use ($search) {
                      $sq->where('size_name', 'like', "%{$search}%");
                  })

                  ->orWhereHas('items.color', function ($cq) use ($search) {
                      $cq->where('color_name', 'like', "%{$search}%");
                  })

                  ->orWhereHas('items.category', function ($catq) use ($search) {
                      $catq->where('category_name', 'like', "%{$search}%");
                  });
            });
        })

        // 📅 DATE FILTER (CALENDAR)
        ->when($date, function ($query) use ($date) {
            $query->whereDate('created_at', $date);
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

        return view('admin.orders.index', compact(
            'orders',
            'search',
            'priceSort',
            'status',
            'date'
        ));
    }

    // ================================
    // UPDATE ORDER STATUS
    // ================================
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