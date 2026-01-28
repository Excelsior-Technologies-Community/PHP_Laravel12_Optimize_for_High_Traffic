@extends('layouts.customer')

@section('content')

<div class="text-center mt-5">
    <h2 class="text-success">🎉 Order Placed Successfully!</h2>
    <p class="mt-3">Thank you for shopping with us.</p>

    <a href="{{ route('customer.products') }}" class="btn btn-primary mt-3">
        Continue Shopping
    </a>
</div>

@endsection