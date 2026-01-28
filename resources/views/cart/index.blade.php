    @extends('layouts.customer')


    @section('content')

    <h2 class="mb-4">My Cart</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Size</th>
                <th>Color</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp

            @forelse($cartItems as $item)
            @php
                $total = $item->price * $item->quantity;
                $grandTotal += $total;
            @endphp
            <tr>
                <td>
                    <img src="{{ asset('images/'.$item->product->image) }}"
                        width="60" class="rounded me-2">
                    {{ $item->product->name }}
                </td>
                <td>{{ $sizes[$item->size_id] ?? '-' }}</td>
                <td>{{ $colors[$item->color_id] ?? '-' }}</td>
                <td>{{ $categories[$item->category_id] ?? '-' }}</td>
               <td>
    <div class="d-flex align-items-center gap-2">

        {{-- ➖ DECREASE --}}
        <form action="{{ route('cart.update.quantity', $item->id) }}"
              method="POST">
            @csrf
            <input type="hidden" name="action" value="decrease">
            <button class="btn btn-outline-secondary btn-sm"
                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                −
            </button>
        </form>

        {{-- QTY --}}
        <strong>{{ $item->quantity }}</strong>

        {{-- ➕ INCREASE --}}
        <form action="{{ route('cart.update.quantity', $item->id) }}"
              method="POST">
            @csrf
            <input type="hidden" name="action" value="increase">
            <button class="btn btn-outline-secondary btn-sm"
                    {{ $item->quantity >= 5 ? 'disabled' : '' }}>
                +
            </button>
        </form>

    </div>
</td>

                <td>₹ {{ $item->price }}</td>
                <td>₹ {{ $total }}</td>
                <td>
                    <form action="{{ route('cart.remove',$item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Cart is empty</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary">
            ← Continue Shopping
        </a>

        <h4 class="mb-0">
            Grand Total: ₹ {{ $grandTotal }}
        </h4>
        <a href="{{ route('address.index') }}" class="btn btn-success">
            Process to Checkout
        </a>
    </div>

    @endsection
