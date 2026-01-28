@extends('layouts.admin')

@section('content')

<style>
    .order-total-box {
        line-height: 1.4;
    }  m       
    .order-total-box .total {
        font-size: 15px;
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

<h2 class="mb-3 fw-bold"> All Customer Orders</h2>

{{-- 🔍 SEARCH + FILTER --}}
<form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Search order, product, customer, address...">
    </div>
    {{-- 📅 DATE FILTER --}}
<div class="col-md-2">
    <input type="date"
           name="date"
           value="{{ request('date') }}"
           class="form-control">
</div>


    <div class="col-md-2">
        <select name="price_sort" class="form-select">
            <option value="">Sort by Price</option>
            <option value="high" {{ request('price_sort')=='high'?'selected':'' }}>High → Low</option>
            <option value="low" {{ request('price_sort')=='low'?'selected':'' }}>Low → High</option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="on_the_way" {{ request('status')=='on_the_way'?'selected':'' }}>On The Way</option>
            <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
            <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Delivered</option>
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary">Apply</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Order</th>
            <th>Customer</th>
            <th>Address</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Date</th>
            <th>Status</th>
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

            <td>
                <strong>{{ $order->customer->name ?? 'Guest' }}</strong><br>
                <small class="text-muted">ID: {{ $order->customer_id }}</small>
            </td>

            <td style="max-width:260px">
                {{ $order->address->address ?? '' }},
                {{ $order->address->city ?? '' }},
                {{ $order->address->state ?? '' }} -
                {{ $order->address->pincode ?? '' }}
            </td>

            {{-- 💰 TOTAL --}}
            <td>
                <div class="order-total-box">
                    <div class="total">₹ {{ number_format($order->total_price, 2) }}</div>

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

            {{-- 💳 PAYMENT --}}
            <td>
                @if($order->payment_method === 'ONLINE')
                    <span class="badge bg-success">Online</span>
                @else
                    <span class="badge bg-warning text-dark">Cash</span>
                @endif
            </td>

            <td>
                {{ $order->created_at->format('d M Y') }}<br>
                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
            </td>

            {{-- 🔄 STATUS --}}
            <td>
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <select name="status"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                        <option value="on_the_way" {{ $order->status=='on_the_way'?'selected':'' }}>On The Way</option>
                        <option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
                        <option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
                    </select>
                </form>

                @php
                    $statusColors = [
                        'pending' => 'secondary',
                        'on_the_way' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                    ];
                @endphp

                <span class="badge bg-{{ $statusColors[$order->status] ?? 'dark' }} mt-1">
                    {{ ucfirst(str_replace('_',' ',$order->status)) }}
                </span>
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
            <td colspan="9">
                <table class="table table-sm table-bordered details-table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
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
                            <td>{{ $item->category->category_name ?? '-' }}</td>
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
                </table>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="9" class="text-center text-muted">
                No orders found
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">
    ← Back to Products
</a>

<div class="d-flex justify-content-center mt-3">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>

@endsection
