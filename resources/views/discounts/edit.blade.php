@extends('layouts.admin')
@section('content')
<style>
    .container-box {
        background: #fff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 6px 25px rgba(0,0,0,0.08);
        margin: 20px auto;
        max-width: 600px;
    }
    .container-box h2 {
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
        color: #2d3436;
    }
    .form-group { margin-bottom: 20px; }
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
        background:#6c5ce7;
        color:white;
    }
    .btn-success:hover { background:#4834d4; }
    .btn-secondary {
        background:#636e72;
        color:white;
    }
    .btn-secondary:hover { background:#2d3436; }
</style>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container-box">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Edit Discount</h2>
        <a href="{{ route('discounts.index') }}" class="btn btn-secondary">⬅ Back</a>
    </div>

    <form action="{{ route('discounts.update', $discount->id) }}" method="POST" style="margin-top:20px;">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="form-group">
            <label for="title">Discount Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $discount->title }}" required>
        </div>

        <!-- Discount Code -->
        <div class="form-group">
            <label for="discount_code">Discount Code</label>
            <input type="text" name="discount_code" id="discount_code" class="form-control" value="{{ $discount->discount_code }}">
        </div>

        <!-- Apply On -->
        <div class="form-group">
            <label for="applyOn">Apply On</label>
            <select name="apply_on" class="form-control" id="applyOn" required>
                <option value="percentage" {{ $discount->apply_on == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="fixed" {{ $discount->apply_on == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
            </select>
        </div>

        <!-- Discount Value -->
        <div class="form-group">
            <label id="valueLabel">Discount Value</label>
            <input type="number" step="0.01" name="value" id="valueInput" class="form-control"
                   value="{{ $discount->value }}" required>
        </div>

        <!-- Apply To -->
        <div class="form-group mt-3">
            <label for="apply_to">Apply To</label>
            <select name="apply_to" id="apply_to" class="form-control" required>
                <option value="all_products" {{ $discount->apply_to == 'all_products' ? 'selected' : '' }}>All Products</option>
                <option value="specific_product" {{ $discount->apply_to == 'specific_product' ? 'selected' : '' }}>Specific Product(s)</option>
            </select>
        </div>

        <!-- Product Dropdown -->
        <div class="form-group mt-3" id="product_dropdown" style="display:none;">
            <label for="product_ids">Select Product(s)</label>
            <select name="product_ids[]" id="product_ids" class="form-control select2" multiple="multiple">
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        {{ in_array($product->id, $selectedProducts ?? []) ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Start Date -->
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                   value="{{ $discount->start_date }}">
        </div>

        <!-- End Date -->
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                   value="{{ $discount->end_date }}">
        </div>

        <!-- Save Button -->
        <button type="submit" class="btn btn-success">💾 Update</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Select2
    $('#product_ids').select2({
        placeholder: "Select product(s)",
        allowClear: true,
        width: '100%'
    });

    const applyOn = document.getElementById("applyOn");
    const valueLabel = document.getElementById("valueLabel");
    const valueInput = document.getElementById("valueInput");

    // Update label + placeholder
    function updateValueLabel() {
        if (applyOn.value === "percentage") {
            valueLabel.innerText = "Discount Value (%)";
            valueInput.setAttribute("placeholder", "Enter percentage value");
            valueInput.setAttribute("max", "100");
        } else {
            valueLabel.innerText = "Discount Value (Amount ₹)";
            valueInput.setAttribute("placeholder", "Enter fixed amount");
            valueInput.removeAttribute("max");
        }
    }

    applyOn.addEventListener("change", updateValueLabel);
    updateValueLabel(); // run on page load

    // Toggle product dropdown
    const applyTo = document.getElementById("apply_to");
    const productDropdown = document.getElementById("product_dropdown");

    function toggleProductDropdown() {
        productDropdown.style.display = (applyTo.value === 'specific_product') ? 'block' : 'none';
    }

    applyTo.addEventListener("change", toggleProductDropdown);
    toggleProductDropdown(); // run on page load
});
</script>
@endsection
