@extends('layouts.customer')

@section('content')

<style>
    .order-total-box {
        line-height: 1.4;
    }
    .order-total-box .total {
        font-size: 16px;
        font-weight: 700;
    }
    .order-total-box .subtotal {
        font-size: 13px;
        color: #6c757d;
    }
    .order-total-box .discount {
        font-size: 13px;
        color: #dc3545;
    }
    .order-details {
        background: #f9fafb;
    }
    .details-table th {
        background: #eef1f4;
        font-size: 13px;
    }
    .details-table td {
        font-size: 13px;
        vertical-align: middle;
    }
</style>

<h3 class="mb-4 fw-bold"> My Orders</h3>

{{-- 🔍 SEARCH + FILTER --}}
<form method="GET" action="{{ route('customer.orders') }}" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Search product, size, color, date...">
    </div>

    <div class="col-md-3">
        <select name="price_sort" class="form-select">
            <option value="">Sort by Price</option>
            <option value="high" {{ request('price_sort')=='high'?'selected':'' }}>
                High → Low
            </option>
            <option value="low" {{ request('price_sort')=='low'?'selected':'' }}>
                Low → High
            </option>
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="on_the_way" {{ request('status')=='on_the_way'?'selected':'' }}>On The Way</option>
            <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
            <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Delivered</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary">Apply</button>
        <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
            Reset
        </a>
    </div>
</form>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Order ID</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th width="90">Action</th>
        </tr>
    </thead>

    <tbody>
    @forelse($orders as $order)

        {{-- SUMMARY --}}
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>
                <strong>{{ $order->id }}</strong>
            </td>

            {{-- 💰 TOTAL --}}
            <td>
                <div class="order-total-box">
                    <div class="total">
                        ₹ {{ number_format($order->total_price, 2) }}
                    </div>

                    @if($order->discount_amount > 0)
                        <div class="subtotal">
                            Subtotal: ₹ {{ number_format($order->subtotal, 2) }}
                        </div>
                        <div class="discount">
                            Discount: -₹ {{ number_format($order->discount_amount, 2) }}
                        </div>
                    @endif
                </div>
            </td>

            {{-- 🎨 STATUS --}}
            @php
                $colors = [
                    'pending' => 'secondary',
                    'on_the_way' => 'info',
                    'shipped' => 'primary',
                    'delivered' => 'success',
                ];
            @endphp
            <td>
                <span class="badge bg-{{ $colors[$order->status] ?? 'dark' }}">
                    {{ ucfirst(str_replace('_',' ',$order->status)) }}
                </span>
            </td>

            <td>
                {{ $order->created_at->format('d M Y') }}<br>
                <small class="text-muted">
                    {{ $order->created_at->format('h:i A') }}
                </small>
            </td>

            <td>
                <button class="btn btn-sm btn-outline-primary view-order"
                        data-target="order-{{ $order->id }}">
                    View
                </button>
            </td>
        </tr>

        {{-- DETAILS --}}
        <tr class="order-details d-none" id="order-{{ $order->id }}">
            <td colspan="6">
                <table class="table table-sm table-bordered details-table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td>{{ $item->size->size_name ?? '-' }}</td>
                            <td>{{ $item->color->color_name ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹ {{ number_format($item->price, 2) }}</td>
                            <td class="text-danger">
                                -₹ {{ number_format($item->discount_amount, 2) }}
                            </td>
                            <td class="fw-bold text-success">
                                ₹ {{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    {{-- ORDER SUMMARY --}}
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Subtotal</strong></td>
                            <td colspan="2">₹ {{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end text-danger"><strong>Discount</strong></td>
                            <td colspan="2">-₹ {{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end fw-bold"><strong>Total Paid</strong></td>
                            <td colspan="2">₹ {{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="6" class="text-center text-muted">
                You have no orders yet
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<a href="{{ route('customer.products') }}" class="btn btn-outline-secondary mt-3">
    ← Continue Shopping
</a>

<div class="d-flex justify-content-center mt-3">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.view-order').forEach(function (btn) {

        btn.addEventListener('click', function () {

            let targetId = this.getAttribute('data-target');
            let row = document.getElementById(targetId);

            if (!row) return;

            // 🔄 toggle
            if (row.classList.contains('d-none')) {
                row.classList.remove('d-none');
                this.textContent = 'Hide';
            } else {
                row.classList.add('d-none');
                this.textContent = 'View';
            }
        });

    });

});
</script>

@endsection
