@extends('layouts.admin')

@section('content')

<h2 class="mb-3">Add Discount</h2>

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

<form action="{{ route('discounts.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Discount Code (Optional)</label>
        <input type="text" name="discount_code" value="{{ old('discount_code') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Apply On</label>
        <select name="apply_on" id="apply_on" class="form-select" required>
            <option value="percentage">Percentage</option>
            <option value="fixed">Fixed Amount</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Value</label>
        <input type="number" name="value" value="{{ old('value') }}" class="form-control" required min="0" step="0.01">
    </div>

    <div class="mb-3">
        <label class="form-label">Apply To</label>
        <select name="apply_to" id="apply_to" class="form-select" required>
            <option value="all_products">All Products</option>
            <option value="specific_product">Specific Products</option>
        </select>
    </div>

    <div class="mb-3" id="product_ids_field" style="display:none;">
        <label class="form-label">Products</label>
        <select name="product_ids[]" class="form-select" multiple>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl/Cmd to select multiple products.</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Save Discount</button>
    <a href="{{ route('discounts.index') }}" class="btn btn-secondary">Back</a>
</form>

<script>
document.getElementById('apply_to').addEventListener('change', function() {
    document.getElementById('product_ids_field').style.display = this.value === 'specific_product' ? 'block' : 'none';
});
</script>

@endsection
