<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Models\Customer;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $giftCards = GiftCard::with('customer')
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.gift-cards.index', compact('giftCards', 'status'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name', 'asc')->get();

        return view('admin.gift-cards.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email|max:255',
            'message' => 'nullable|string',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $code = strtoupper(uniqid() . bin2hex(random_bytes(4)));

        GiftCard::create([
            'code' => $code,
            'amount' => $request->amount,
            'balance' => $request->amount,
            'customer_id' => $request->customer_id,
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'message' => $request->message,
            'expires_at' => $request->expires_at,
            'status' => 'active',
        ]);

        return redirect()->route('admin.gift-cards.index')->with('success', 'Gift card created successfully');
    }

    public function edit(GiftCard $giftCard)
    {
        $customers = Customer::orderBy('name', 'asc')->get();

        return view('admin.gift-cards.edit', compact('giftCard', 'customers'));
    }

    public function update(Request $request, GiftCard $giftCard)
    {
        $request->validate([
            'balance'          => 'required|numeric|min:0',
            'status'           => 'required|in:active,used,expired,cancelled,inactive',
            'expires_at'       => 'nullable|date',
            'recipient_name'   => 'nullable|string|max:255',
            'recipient_email'  => 'nullable|email|max:255',
            'message'          => 'nullable|string',
            'customer_id'      => 'nullable|exists:customers,id',
        ]);

        $giftCard->update([
            'balance'         => $request->balance,
            'status'          => $request->status,
            'expires_at'      => $request->expires_at,
            'recipient_name'  => $request->recipient_name ?? $giftCard->recipient_name,
            'recipient_email' => $request->recipient_email ?? $giftCard->recipient_email,
            'message'         => $request->message,
            'customer_id'     => $request->customer_id,
        ]);

        return redirect()->route('admin.gift-cards.index')->with('success', 'Gift card updated successfully');
    }

    public function destroy(GiftCard $giftCard)
    {
        $giftCard->delete();

        return redirect()->route('admin.gift-cards.index')->with('success', 'Gift card deleted successfully');
    }
}
