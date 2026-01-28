@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif; background: #f5f6fa; }
    .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px 40px; border-radius: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    h2 { font-weight: 700; font-size: 28px; color: #2d3436; margin-bottom: 30px; text-align: center; }
    .detail-group { margin-bottom: 20px; }
    .detail-label { font-weight: 600; color: #636e72; margin-bottom: 6px; display: block; }
    .detail-value { font-size: 18px; color: #2d3436; border: 1px solid #dfe6e9; border-radius: 8px; padding: 10px 14px; background: #f8f9fa; }
    .back-btn { display: block; margin-top: 30px; text-align: center; background: #6c5ce7; color: white; padding: 12px; border-radius: 10px; font-weight: 600; text-decoration: none; transition: 0.3s; }
    .back-btn:hover { background: #4834d4; transform: scale(1.03); }
    ul.product-list { list-style: disc; margin-left: 20px; }
</style>

<div class="container">
    <h2>Discount Details</h2>

    <div class="detail-group">
        <label class="detail-label">ID</label>
        <div class="detail-value">{{ $discount->id }}</div>
    </div>

    <div class="detail-group">
        <label class="detail-label">Title</label>
        <div class="detail-value">{{ $discount->title }}</div>
    </div>

    <div class="detail-group">
        <label class="detail-label">Discount Code</label>
        <div class="detail-value">{{ $discount->discount_code ?? '—' }}</div>
    </div>

    <div class="detail-group">
        <label class="detail-label">Apply On</label>
        <div class="detail-value">{{ ucfirst($discount->apply_on) }}</div>
    </div>

    <div class="detail-group">
        <label class="detail-label">Value</label>
        <div class="detail-value">
            {{ $discount->apply_on === 'percentage' ? $discount->value . '%' : '₹' . number_format($discount->value, 2) }}
        </div>
    </div>

    <div class="detail-group">
        <label class="detail-label">Apply To</label>
        <div class="detail-value">
            {{ $discount->apply_to === 'all_products' ? 'All Products' : 'Specific Products' }}
        </div>
    </div>

    @if($discount->apply_to === 'specific_product')
        <div class="detail-group">
            <label class="detail-label">Applicable Products</label>
            <div class="detail-value">
                @if($products->count())
                    <ul class="product-list">
                        @foreach($products as $product)
                            <li>{{ $product->name }}</li>
                        @endforeach
                    </ul>
                @else
                    No products found.
                @endif
            </div>
        </div>
    @endif

    <div class="detail-group">
        <label class="detail-label">Created At</label>
        <div class="detail-value">{{ $discount->created_at->format('d M, Y H:i') }}</div>
    </div>

    <div class="detail-group">
        <label class="detail-label">Updated At</label>
        <div class="detail-value">{{ $discount->updated_at->format('d M, Y H:i') }}</div>
    </div>

    <a href="{{ route('discounts.index') }}" class="back-btn">⬅ Back to Discounts</a>
</div>

@endsection
