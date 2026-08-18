@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">My Wallet</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- BALANCE CARD --}}
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body text-center py-5">
        <h5 class="text-muted mb-2">Available Balance</h5>
        <h1 class="display-4 fw-bold text-primary mb-3">₹ {{ number_format($wallet->balance, 2) }}</h1>
        <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-primary">
            View Transaction History
        </a>
    </div>
</div>

{{-- RECHARGE FORM --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Recharge Wallet</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('wallet.recharge') }}" method="POST">
            @csrf
            <div class="input-group mb-3" style="max-width: 400px;">
                <span class="input-group-text">₹</span>
                <input type="number"
                       name="amount"
                       class="form-control"
                       placeholder="Enter amount"
                       min="1"
                       step="0.01"
                       required>
                <button class="btn btn-primary" type="submit">Recharge</button>
            </div>
        </form>
    </div>
</div>

{{-- RECENT TRANSACTIONS --}}
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Transactions</h5>
        <a href="{{ route('wallet.transactions') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wallet->transactions as $tx)
                <tr>
                    <td>{{ $tx->created_at->format('d M Y h:i A') }}</td>
                    <td>
                        <span class="badge bg-{{ $tx->type == 'credit' ? 'success' : 'danger' }}">
                            {{ ucfirst($tx->type) }}
                        </span>
                    </td>
                    <td class="fw-bold">₹ {{ number_format($tx->amount, 2) }}</td>
                    <td>{{ $tx->note ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No transactions yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
