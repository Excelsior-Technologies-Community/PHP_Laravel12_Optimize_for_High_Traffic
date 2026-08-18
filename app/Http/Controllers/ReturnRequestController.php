<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function index(Request $request)
    {
        $customerId = auth('customer')->id();
        $status     = $request->status;

        $returnRequests = ReturnRequest::with(['order', 'orderItem.product'])
            ->where('customer_id', $customerId)
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $orders = Order::where('customer_id', $customerId)
            ->whereIn('status', ['paid', 'pending', 'shipped', 'delivered'])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'created_at', 'status']);

        $orderItems = OrderItem::whereHas('order', fn($q) => $q->where('customer_id', $customerId))
            ->with('product')
            ->get();

        return view('returns.index', compact('returnRequests', 'status', 'orders', 'orderItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'reason'        => 'required|string|min:10',
        ]);

        $customerId = auth('customer')->id();

        // Security: order customer_id check
        $order = Order::where('id', $request->order_id)
            ->where('customer_id', $customerId)
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Invalid order selected');
        }

        // Duplicate check
        $exists = ReturnRequest::where('customer_id', $customerId)
            ->where('order_item_id', $request->order_item_id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Return request already submitted for this item');
        }

        ReturnRequest::create([
            'customer_id'   => $customerId,
            'order_id'      => $request->order_id,
            'order_item_id' => $request->order_item_id,
            'reason'        => $request->reason,
            'status'        => 'pending',
        ]);

        return redirect()->back()->with('success', 'Return request submitted successfully');
    }

    public function updateStatus(Request $request, ReturnRequest $returnRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed',
        ]);

        $returnRequest->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Return request status updated');
    }
}
