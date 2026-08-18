<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Models\CustomerWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class CustomerGiftCardController extends Controller
{
    private function getGiftCards()
    {
        return GiftCard::where('customer_id', auth('customer')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function index()
    {
        $giftCards = $this->getGiftCards();
        return view('gift-cards.index', compact('giftCards'));
    }

    public function purchaseForm()
    {
        $giftCards = $this->getGiftCards();
        return view('gift-cards.index', compact('giftCards'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'amount'          => 'required|numeric|min:1|max:10000',
            'recipient_name'  => 'required|string|max:255',
            'recipient_email' => 'required|email|max:255',
            'message'         => 'nullable|string|max:500',
        ]);

        $code = strtoupper(uniqid() . bin2hex(random_bytes(4)));

        GiftCard::create([
            'code'            => $code,
            'amount'          => $request->amount,
            'balance'         => $request->amount,
            'customer_id'     => auth('customer')->id(),
            'recipient_name'  => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'message'         => $request->message,
            'status'          => 'active',
        ]);

        return redirect()->route('gift-cards.index')
            ->with('success', 'Gift card purchased successfully');
    }

    public function redeemForm()
    {
        $giftCards = $this->getGiftCards();
        return view('gift-cards.index', compact('giftCards'));
    }

    public function redeem(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $customerId = auth('customer')->id();

        $giftCard = GiftCard::where('code', $request->code)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$giftCard) {
            return redirect()->back()->with('error', 'Invalid or expired gift card code');
        }

        if ($giftCard->balance <= 0) {
            return redirect()->back()->with('error', 'Gift card has no balance');
        }

        $giftCard->customer_id = $customerId;
        $giftCard->save();

        // CustomerWallet model use karo (App\Models\CustomerWallet)
        $wallet = CustomerWallet::firstOrCreate(
            ['customer_id' => $customerId],
            ['balance' => 0]
        );
        $wallet->increment('balance', $giftCard->balance);

        WalletTransaction::create([
            'wallet_id'      => $wallet->id,
            'type'           => 'credit',
            'amount'         => $giftCard->balance,
            'reference_type' => GiftCard::class,
            'reference_id'   => $giftCard->id,
            'note'           => 'Gift card redeemed: ' . $giftCard->code,
        ]);

        $giftCard->update(['balance' => 0, 'status' => 'used']);

        return redirect()->route('gift-cards.index')
            ->with('success', 'Gift card redeemed! ₹' . number_format($wallet->balance, 2) . ' added to wallet');
    }
}
