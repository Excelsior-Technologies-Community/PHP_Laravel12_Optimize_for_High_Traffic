@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Gift Card Management</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="GET" action="{{ route('admin.gift-cards.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search by code or recipient name/email">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="active"    {{ request('status') == 'active'    ? 'selected' : '' }}>Active</option>
            <option value="used"      {{ request('status') == 'used'      ? 'selected' : '' }}>Used</option>
            <option value="expired"   {{ request('status') == 'expired'   ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="inactive"  {{ request('status') == 'inactive'  ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary">Search</button>
        <a href="{{ route('admin.gift-cards.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<a href="{{ route('admin.gift-cards.create') }}" class="btn btn-primary mb-3">+ Add Gift Card</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Amount</th>
            <th>Balance</th>
            <th>Recipient</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Expires At</th>
            <th>Created</th>
            <th width="130">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($giftCards as $giftCard)
        <tr>
            <td>{{ $giftCard->id }}</td>
            <td><code>{{ $giftCard->code }}</code></td>
            <td>₹{{ number_format($giftCard->amount, 2) }}</td>
            <td>
                <span class="{{ $giftCard->balance > 0 ? 'text-success fw-bold' : 'text-muted' }}">
                    ₹{{ number_format($giftCard->balance, 2) }}
                </span>
            </td>
            <td>
                <div>{{ $giftCard->recipient_name }}</div>
                <small class="text-muted">{{ $giftCard->recipient_email }}</small>
            </td>
            <td>
                @if($giftCard->customer)
                    <span class="badge bg-info text-dark">{{ $giftCard->customer->name }}</span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                @php
                    $badgeMap = [
                        'active'    => 'success',
                        'used'      => 'warning',
                        'expired'   => 'danger',
                        'cancelled' => 'secondary',
                        'inactive'  => 'dark',
                    ];
                @endphp
                <span class="badge bg-{{ $badgeMap[$giftCard->status] ?? 'secondary' }}">
                    {{ ucfirst($giftCard->status) }}
                </span>
            </td>
            <td>
                {{ $giftCard->expires_at ? \Carbon\Carbon::parse($giftCard->expires_at)->format('d M Y') : '—' }}
            </td>
            <td>{{ $giftCard->created_at->format('d M Y') }}</td>
            <td>
                <a href="{{ route('admin.gift-cards.edit', $giftCard->id) }}"
                   class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.gift-cards.destroy', $giftCard->id) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this gift card?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="text-center text-muted">No gift cards found</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $giftCards->links('pagination::bootstrap-5') }}
</div>

@endsection
