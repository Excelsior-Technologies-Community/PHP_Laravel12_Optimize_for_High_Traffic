@extends('layouts.admin')

@section('content')

<!-- 🌈 STYLES -->
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f6fa;
    margin: 0;
    padding: 0;
}
.container-box {
    background: #fff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.08);
    margin: 50px auto;
    max-width: 600px;
}
.container-box h2 {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #2d3436;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #2d3436;
}
.form-control {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #dfe6e9;
    font-size: 15px;
    transition: 0.2s;
}
.form-control:focus {
    border-color: #6c5ce7;
    outline: none;
    box-shadow: 0 0 6px rgba(108,92,231,0.4);
}
.btn {
    padding: 10px 16px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: 0.2s;
}
.btn-success {
    background: #6c5ce7;
    color: white;
}
.btn-success:hover {
    background: #4834d4;
}
.btn-secondary {
    background: #636e72;
    color: white;
}
.btn-secondary:hover {
    background: #2d3436;
}
</style>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- FORM -->
<div class="container-box">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>{{ isset($discount) ? 'Edit Discount' : 'Add Discount' }}</h2>
        <a href="{{ route('discounts.index') }}" class="btn btn-secondary">⬅ Back</a>
    </div>

    <form action="{{ isset($discount) ? route('discounts.update', $discount->id) : route('discounts.store') }}" method="POST" style="margin-top:20px;">
        @csrf
        @if(isset($discount)) @method('PUT') @endif

        <!-- Title -->
        <div class="form-group">
            <label>Discount Title</label>
            <input type="text" name="title" class="form-control" required value="{{ $discount->title ?? old('title') }}">
        </div>

        <!-- Discount Code -->
        <div class="form-group">
            <label>Discount Code</label>
            <input type="text" name="discount_code" class="form-control" value="{{ $discount->discount_code ?? old('discount_code') }}">
        </div>

        <!-- Apply On -->
        <div class="form-group">
            <label>Apply On</label>
            <select name="apply_on" id="applyOn" class="form-control" required>
                <option value="percentage" {{ (isset($discount) && $discount->apply_on=='percentage') ? 'selected' : '' }}>Percentage (%)</option>
                <option value="fixed" {{ (isset($discount) && $discount->apply_on=='fixed') ? 'selected' : '' }}>Fixed Amount</option>
            </select>
        </div>

        <!-- Discount Value -->
        <div class="form-group">
            <label id="valueLabel">Discount Value</label>
            <input type="number" step="0.01" name="value" id="valueInput" class="form-control" placeholder="Enter value" required value="{{ $discount->value ?? old('value') }}">
        </div>

        <!-- Apply To -->
        <div class="form-group">
            <label>Apply To</label>
            <select name="apply_to" id="apply_to" class="form-control" required>
                <option value="all_products" {{ (isset($discount) && $discount->apply_to=='all_products') ? 'selected' : '' }}>All Products</option>
                <option value="specific_product" {{ (isset($discount) && $discount->apply_to=='specific_product') ? 'selected' : '' }}>Specific Product(s)</option>
            </select>
        </div>

        <!-- Product Dropdown -->
        <div class="form-group" id="product_dropdown" style="display:none;">
            <label>Select Product(s)</label>
            <select name="product_ids[]" id="product_id" class="form-control select2" multiple>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ isset($selectedProducts) && in_array($product->id,$selectedProducts) ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Start / End Date -->
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $discount->start_date ?? old('start_date') }}">
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $discount->end_date ?? old('end_date') }}">
        </div>

        <!-- Save Button -->
        <button type="submit" class="btn btn-success">💾 {{ isset($discount) ? 'Update' : 'Save' }}</button>
    </form>
</div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Dynamic Behavior -->
<script>
$(document).ready(function(){

    // Initialize Select2
    $('#product_id').select2({ placeholder:"Select product(s)", allowClear:true });

    // Update label for fixed/percentage
    function updateLabel(){
        if($('#applyOn').val()=='fixed'){
            $('#valueLabel').text('Discount Value (Amount ₹)');
            $('#valueInput').attr('placeholder','Enter fixed amount').attr('max','');
        } else {
            $('#valueLabel').text('Discount Value (%)');
            $('#valueInput').attr('placeholder','Enter percentage value').attr('max','100');
        }
    }

    // Toggle product dropdown
    function toggleProducts(){
        if($('#apply_to').val()=='specific_product'){
            $('#product_dropdown').show();
        } else { $('#product_dropdown').hide(); }
    }

    $('#applyOn').change(updateLabel);
    $('#apply_to').change(toggleProducts);

    // Initial load
    updateLabel();
    toggleProducts();
});
</script>

@endsection
