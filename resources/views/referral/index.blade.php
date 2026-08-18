@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">Referral Program</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('info'))
<div class="alert alert-info">{{ session('info') }}</div>
@endif

<div class="row g-4">
    {{-- REFERRAL CODE --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Your Referral Code</h5>

                @if($referralCode)
                <div class="input-group mt-3 mb-3" style="max-width: 300px; margin: 0 auto;">
                    <input type="text"
                           id="referralCode"
                           class="form-control"
                           value="{{ $referralCode->code }}"
                           readonly>
                    <button class="btn btn-outline-primary"
                            type="button"
                            onclick="copyReferralCode()">
                        Copy
                    </button>
                </div>

                <div class="mt-3">
                    <h6 class="text-muted">Total Referrals</h6>
                    <span class="badge bg-primary fs-5">{{ $referralCode->referrals_count ?? 0 }}</span>
                </div>

                <div class="mt-4">
                    <h6 class="text-muted">Earnings</h6>
                    <span class="badge bg-success fs-5">₹ {{ number_format($totalEarnings ?? 0, 2) }}</span>
                </div>
                @else
                <p class="text-muted mt-3">You don't have a referral code yet.</p>
                <form action="{{ route('referral.generate') }}" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-primary">Generate Referral Code</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- APPLY CODE --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Apply Referral Code</h5>
                <p class="text-muted">Enter a friend's referral code and earn rewards.</p>
                <form action="{{ route('referral.apply') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text"
                               name="code"
                               class="form-control"
                               placeholder="Enter referral code"
                               required>
                        <button class="btn btn-success" type="submit">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- HOW IT WORKS --}}
<div class="card shadow-sm mt-4">
    <div class="card-body">
        <h5 class="card-title">How Referrals Work</h5>
        <ol class="mb-0 ps-3">
            <li class="mb-2">Share your unique referral code with friends.</li>
            <li class="mb-2">When a friend signs up using your code, both of you earn rewards.</li>
            <li class="mb-2">Earnings are credited directly to your wallet.</li>
            <li class="mb-2">You can track your referrals and earnings here.</li>
        </ol>
    </div>
</div>

<script>
function copyReferralCode() {
    var copyText = document.getElementById("referralCode");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
}
</script>

@endsection
