@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Add Gift Card</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.gift-cards.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Amount</label>
        <input type="number" name="amount" value="{{ old('amount') }}" class="form-control">
        @error('amount')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Recipient Name</label>
        <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" class="form-control">
        @error('recipient_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Recipient Email</label>
        <input type="email" name="recipient_email" value="{{ old('recipient_email') }}" class="form-control">
        @error('recipient_email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control">{{ old('message') }}</textarea>
        @error('message')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Expires At</label>
        <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="form-control">
        @error('expires_at')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Customer</label>
        <select name="customer_id" class="form-control">
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                {{ $customer->name }}
            </option>
            @endforeach
        </select>
        @error('customer_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="used" {{ old('status') == 'used' ? 'selected' : '' }}>Used</option>
            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        @error('status')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-success">Save Gift Card</button>
    <a href="{{ route('admin.gift-cards.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
