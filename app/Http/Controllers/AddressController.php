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
