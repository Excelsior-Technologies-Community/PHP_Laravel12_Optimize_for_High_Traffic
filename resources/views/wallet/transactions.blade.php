@extends('layouts.customer')

@section('content')

<h3 class="mb-4 fw-bold">Transaction History</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
            @forelse($transactions as $tx)
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
                    <td colspan="5" class="text-center text-muted">No transactions found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $transactions->links('pagination::bootstrap-5') }}
</div>

@endsection
