<?php

namespace App\Http\Controllers;

use App\Models\CustomerWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class CustomerWalletController extends Controller
{
    // 💰 WALLET DASHBOARD
    public function index(Request $request)
    {
        $customerId = auth('customer')->id();

        $wallet = CustomerWallet::with(['transactions' => function ($query) {
                $query->latest();
            }])
            ->where('customer_id', $customerId)
            ->first();

        if (!$wallet) {
            $wallet = CustomerWallet::create([
                'customer_id' => $customerId,
                'balance' => 0,
            ]);
        }

        return view('wallet.index', compact('wallet'));
    }

    // ➕ RECHARGE WALLET
    public function recharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $customerId = auth('customer')->id();

        $wallet = CustomerWallet::where('customer_id', $customerId)->firstOrFail();

        $wallet->increment('balance', $request->amount);

        WalletTransaction::create([
            'wallet_id'   => $wallet->id,
            'type'        => 'credit',
            'amount'      => $request->amount,
            'note'        => 'Wallet recharge',
        ]);

        return redirect()->back()
            ->with('success', 'Wallet recharged successfully');
    }

    // 📜 TRANSACTION HISTORY
    public function transactions(Request $request)
    {
        $customerId = auth('customer')->id();

        $wallet = CustomerWallet::where('customer_id', $customerId)->first();

        if (!$wallet) {
            $wallet = CustomerWallet::create([
                'customer_id' => $customerId,
                'balance' => 0,
            ]);
        }

        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->latest()
            ->paginate(15);

        return view('wallet.transactions', compact('transactions'));
    }
}
