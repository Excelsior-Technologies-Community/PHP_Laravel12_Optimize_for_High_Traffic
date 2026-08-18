@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">Gift Cards</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('info'))
<div class="alert alert-info">{{ session('info') }}</div>
@endif

<div class="row g-4 mb-4">
    {{-- GIFT CARDS --}}
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Gift Cards</h5>
                <a href="{{ route('gift-cards.purchase.form') }}" class="btn btn-warning btn-sm">Purchase Gift Card</a>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($giftCards as $card)
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $card->code }}</h6>
                                        <p class="mb-1">Balance: <strong>₹ {{ number_format($card->balance, 2) }}</strong></p>
                                        <small class="text-muted">Expires: {{ $card->expires_at ? $card->expires_at->format('d M Y') : 'No Expiry' }}</small>
                                    </div>
                                    <span class="badge bg-{{ $card->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($card->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-4">
                        No gift cards yet. Purchase one to get started!
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- REDEEM FORM --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Redeem Gift Card</h5>
                <form action="{{ route('gift-cards.redeem') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Gift Card Code</label>
                        <input type="text"
                               name="code"
                               class="form-control"
                               placeholder="Enter code"
                               required>
                    </div>
                    <button class="btn btn-primary w-100">Redeem</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
