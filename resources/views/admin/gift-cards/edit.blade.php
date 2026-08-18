@extends('layouts.admin')

@section('content')

<h2 class="mb-4">Edit Gift Card</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.gift-cards.update', $giftCard->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Code</label>
        <input type="text" class="form-control" value="{{ $giftCard->code }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Original Amount</label>
        <input type="number" class="form-control" value="{{ $giftCard->amount }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Balance <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="balance" value="{{ old('balance', $giftCard->balance) }}" class="form-control" required>
        @error('balance')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Recipient Name</label>
        <input type="text" name="recipient_name" value="{{ old('recipient_name', $giftCard->recipient_name) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Recipient Email</label>
        <input type="email" name="recipient_email" value="{{ old('recipient_email', $giftCard->recipient_email) }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control">{{ old('message', $giftCard->message) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Expires At</label>
        <input type="date" name="expires_at"
               value="{{ old('expires_at', $giftCard->expires_at ? \Carbon\Carbon::parse($giftCard->expires_at)->format('Y-m-d') : '') }}"
               class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Customer</label>
        <select name="customer_id" class="form-control">
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}" {{ old('customer_id', $giftCard->customer_id) == $customer->id ? 'selected' : '' }}>
                {{ $customer->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active"      {{ old('status', $giftCard->status) == 'active'      ? 'selected' : '' }}>Active</option>
            <option value="used"        {{ old('status', $giftCard->status) == 'used'        ? 'selected' : '' }}>Used</option>
            <option value="expired"     {{ old('status', $giftCard->status) == 'expired'     ? 'selected' : '' }}>Expired</option>
            <option value="cancelled"   {{ old('status', $giftCard->status) == 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
            <option value="inactive"    {{ old('status', $giftCard->status) == 'inactive'    ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">Update Gift Card</button>
    <a href="{{ route('admin.gift-cards.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection
