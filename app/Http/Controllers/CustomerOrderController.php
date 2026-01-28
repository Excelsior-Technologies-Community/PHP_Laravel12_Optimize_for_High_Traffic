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
