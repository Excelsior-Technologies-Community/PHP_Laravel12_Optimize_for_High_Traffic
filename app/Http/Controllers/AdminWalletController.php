<?php

namespace App\Http\Controllers;

use App\Models\CustomerWallet;
use App\Models\WalletTransaction;
use App\Models\Customer;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function index(Request $request)
    {
        $wallets = CustomerWallet::with(['customer', 'transactions' => function ($query) {
            $query->latest()->take(1);
        }])->paginate(15);

        return view('admin.wallets.index', compact('wallets'));
    }

    public function recharge(Request $request, CustomerWallet $wallet)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
        ]);

        $amount = $request->amount;

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'credit',
            'amount' => $amount,
            'reference_type' => 'admin_recharge',
            'note' => $request->note,
        ]);

        $wallet->increment('balance', $amount);

        return back()->with('success', 'Wallet recharged successfully');
    }

    public function transactions()
    {
        $transactions = WalletTransaction::with('wallet.customer')
            ->latest()
            ->paginate(20);

        return view('admin.wallets.transactions', compact('transactions'));
    }
}
