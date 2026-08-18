<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\CustomerWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function index()
    {
        $customerId = auth('customer')->id();

        $referral = Referral::where('customer_id', $customerId)->first();

        $totalReferrals = Referral::where('referred_by', $customerId)->count();

        $referralCode = $referral;
        $totalEarnings = 0;

        if ($referral) {
            $totalEarnings = WalletTransaction::where('reference_type', Referral::class)
                ->where('reference_id', $referral->id)
                ->where('type', 'credit')
                ->sum('amount');
        }

        return view('referral.index', compact('referral', 'referralCode', 'totalReferrals', 'totalEarnings'));
    }

    public function generateCode()
    {
        $customerId = auth('customer')->id();

        $referral = Referral::where('customer_id', $customerId)->first();

        if (!$referral) {
            $code = strtoupper(Str::random(8));

            Referral::create([
                'customer_id' => $customerId,
                'referral_code' => $code,
                'status' => 'active',
            ]);
        }

        return redirect()->route('referral.index')
            ->with('success', 'Referral code generated');
    }

    public function apply(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string|max:50',
        ]);

        $customerId = auth('customer')->id();

        $referral = Referral::where('referral_code', $request->referral_code)
            ->where('status', 'active')
            ->first();

        if (!$referral) {
            return redirect()->back()
                ->with('error', 'Invalid referral code');
        }

        if ($referral->customer_id == $customerId) {
            return redirect()->back()
                ->with('error', 'You cannot use your own referral code');
        }

        if ($referral->used_count >= $referral->max_uses) {
            return redirect()->back()
                ->with('error', 'This referral code has expired');
        }

        $referral->increment('used_count');

        $wallet = CustomerWallet::where('customer_id', $customerId)->firstOrFail();
        $wallet->increment('balance', 50);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'credit',
            'amount' => 50,
            'reference_type' => Referral::class,
            'reference_id' => $referral->id,
            'note' => 'Referral bonus',
        ]);

        $referredByWallet = CustomerWallet::where('customer_id', $referral->customer_id)->first();
        if ($referredByWallet) {
            $referredByWallet->increment('balance', 50);

            WalletTransaction::create([
                'wallet_id' => $referredByWallet->id,
                'type' => 'credit',
                'amount' => 50,
                'reference_type' => Referral::class,
                'reference_id' => $referral->id,
                'note' => 'Referral reward',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Referral code applied! ₹50 added to your wallet');
    }
}
