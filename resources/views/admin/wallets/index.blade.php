@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Wallet List</h2>

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

<p class="text-muted">Total Wallets: {{ $wallets->count() }}</p>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Balance</th>
            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($wallets as $wallet)
        <tr>
            <td>{{ $wallet->id }}</td>
            <td>{{ $wallet->customer->name ?? 'N/A' }}</td>
            <td>{{ $wallet->customer->email ?? 'N/A' }}</td>
            <td>{{ $wallet->balance }}</td>
            <td>
                <form action="{{ route('admin.wallets.recharge', $wallet->id) }}" method="POST" class="d-inline">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="number" name="amount" class="form-control" placeholder="Amount" required min="0.01" step="0.01">
                        <button type="submit" class="btn btn-success">Recharge</button>
                    </div>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">No wallets found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $wallets->links('pagination::bootstrap-5') }}
</div>

@endsection
