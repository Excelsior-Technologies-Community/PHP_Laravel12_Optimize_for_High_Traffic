<?php

namespace App\Http\Controllers;

use App\Models\BackInStockNotification;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class BackInStockNotificationController extends Controller
{
    // 🔔 REQUEST BACK IN STOCK NOTIFICATION
    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'variant_id'  => 'nullable|exists:product_variants,id',
        ]);

        BackInStockNotification::create([
            'customer_id' => auth('customer')->id(),
            'product_id'  => $request->product_id,
            'variant_id'  => $request->variant_id,
            'notified'    => false,
        ]);

        return redirect()->back()
            ->with('success', 'You will be notified when the product is back in stock');
    }

    // ❌ REMOVE NOTIFICATION
    public function destroy(BackInStockNotification $notification)
    {
        if ($notification->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notification removed');
    }
}
