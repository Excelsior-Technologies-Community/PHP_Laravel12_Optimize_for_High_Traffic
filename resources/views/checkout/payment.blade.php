@extends('layouts.customer')

@section('content')

<style>
    .payment-title { font-weight: 700; letter-spacing: 0.5px; }
    .checkout-card { border: none; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); margin-bottom: 25px; }
    .checkout-card h5 { font-weight: 600; margin-bottom: 15px; }
    .address-box p { margin-bottom: 4px; font-size: 14px; color: #555; }
    .order-item { padding: 12px 0; border-bottom: 1px dashed #ddd; }
    .order-item:last-child { border-bottom: none; }
    .order-item-name { font-weight: 600; }
    .order-meta { font-size: 13px; color: #666; }
    .price-box { background: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 10px; }
    .total-amount { font-size: 20px; font-weight: 700; color: #198754; }
    .payment-option { border: 1px solid #ddd; border-radius: 10px; padding: 12px 15px; cursor: pointer; transition: 0.2s; }
    .payment-option:hover { border-color: #198754; background: #f6fffa; }
    .place-order-btn { font-size: 18px; font-weight: 600; padding: 14px; border-radius: 12px; }
</style>

<h2 class="mb-4 text-center payment-title">Secure Payment</h2>

<div class="row justify-content-center">

    {{-- 📍 ADDRESS --}}
    <div class="col-md-8">
        <div class="card checkout-card">
            <div class="card-body address-box">
                <h5>📍 Delivery Address</h5>
                <p><strong>Customer Name:</strong> {{ auth('customer')->user()->name }}</p>
                <p><strong>Address:</strong> {{ $address['address'] }}</p>
                <p>{{ $address['city'] }}, {{ $address['state'] }} - {{ $address['pincode'] }}</p>
            </div>
        </div>
    </div>

    {{-- 🛒 ORDER SUMMARY --}}
    <div class="col-md-8">
        <div class="card checkout-card">
            <div class="card-body">
                <h5>🛒 Order Summary</h5>

                @php $grandTotal = 0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $total = $item->price * $item->quantity;
                        $grandTotal += $total;
                    @endphp
                    <div class="order-item">
                        <div class="order-item-name">{{ $item->product->name }}</div>
                        <div class="order-meta">
                            Size: {{ $sizes[$item->size_id] ?? '' }} |
                            Color: {{ $colors[$item->color_id] ?? '' }} |
                            Qty: {{ $item->quantity }}
                        </div>
                    </div>
                @endforeach

                {{-- 🎁 DISCOUNT DROPDOWN --}}
                @if($specificDiscounts->count() || $allProductDiscounts->count())
                    <label class="mt-3 fw-bold">🎁 Apply Discount</label>
                    <select id="discountSelect" class="form-select">
                        <option value="">-- No Discount --</option>

                        @foreach($specificDiscounts as $discount)
                            <option data-type="{{ $discount->apply_on }}" data-value="{{ $discount->value }}">
                                {{ $discount->title }} (Specific)
                            </option>
                        @endforeach

                        @foreach($allProductDiscounts as $discount)
                            <option data-type="{{ $discount->apply_on }}" data-value="{{ $discount->value }}">
                                {{ $discount->title }} (All Products)
                            </option>
                        @endforeach
                    </select>
                @endif

                {{-- 💰 TOTAL --}}
                <div class="price-box">
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <span>₹ <span id="originalTotal">{{ $grandTotal }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between text-danger">
                        <span>Discount</span>
                        <span>- ₹ <span id="discountAmount">0</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between total-amount">
                        <span>Payable</span>
                        <span>₹ <span id="finalAmount">{{ $grandTotal }}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 💳 PAYMENT --}}
    <div class="col-md-8">
        <div class="card checkout-card">
            <div class="card-body">
                <h5>💳 Payment Method</h5>

                <form id="paymentForm" method="POST" action="{{ route('place.order') }}">
                    @csrf
                    <input type="hidden" name="discount" id="discountInput" value="0">

                    <label class="payment-option d-flex align-items-center mb-2">
                        <input type="radio" name="payment_method" value="COD" checked class="me-2">
                        Cash on Delivery
                    </label>

                    <label class="payment-option d-flex align-items-center mb-3">
                        <input type="radio" name="payment_method" value="ONLINE" class="me-2">
                        Online Payment
                    </label>

                    <button type="submit" class="btn btn-success w-100 place-order-btn">
                        Place Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
let originalTotal = {{ $grandTotal }};

document.getElementById('discountSelect')?.addEventListener('change', function () {
    let opt = this.options[this.selectedIndex];
    let type = opt.dataset.type;
    let value = parseFloat(opt.dataset.value || 0);
    let discount = 0;

    if (type === 'percentage') discount = (originalTotal * value) / 100;
    if (type === 'fixed') discount = value;

    discount = Math.min(discount, originalTotal);

    document.getElementById('discountAmount').innerText = discount.toFixed(2);
    document.getElementById('finalAmount').innerText = (originalTotal - discount).toFixed(2);
    document.getElementById('discountInput').value = discount.toFixed(2);
});

document.querySelector('.place-order-btn').addEventListener('click', function (e) {

    let method = document.querySelector('input[name="payment_method"]:checked').value;
    if (method === 'COD') return;

    e.preventDefault();
    let payable = document.getElementById('finalAmount').innerText;

    fetch("{{ route('razorpay.order') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ amount: payable })
    })
    .then(res => res.json())
    .then(data => {
        let rzp = new Razorpay({
            key: data.key,
            amount: data.amount * 100,
            currency: "INR",
            order_id: data.order_id,
            handler: function (response) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('razorpay.verify') }}";
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                    <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                    <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                    <input type="hidden" name="discount" value="${document.getElementById('discountInput').value}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
        rzp.open();
    });
});
</script>

@endsection
