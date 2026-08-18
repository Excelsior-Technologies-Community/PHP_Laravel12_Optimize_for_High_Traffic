@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Wallet Transactions</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Reference Type</th>
            <th>Reference ID</th>
            <th>Note</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $transaction)
        <tr>
            <td>{{ $transaction->id }}</td>
            <td>{{ $transaction->wallet->customer->name ?? 'N/A' }}</td>
            <td>
                @if($transaction->type == 'credit')
                <span class="badge bg-success">Credit</span>
                @else
                <span class="badge bg-danger">Debit</span>
                @endif
            </td>
            <td>{{ $transaction->amount }}</td>
            <td>{{ $transaction->reference_type ?? '-' }}</td>
            <td>{{ $transaction->reference_id ?? '-' }}</td>
            <td>{{ $transaction->note ?? '-' }}</td>
            <td>{{ $transaction->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted">No transactions found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $transactions->links('pagination::bootstrap-5') }}
</div>

@endsection
