@extends('layouts.customer')

@section('content')

<h2 class="mb-4 text-center">Delivery Address</h2>

@if(session('success'))
<div class="alert alert-success text-center">
    {{ session('success') }}
</div>
@endif

<div class="row">

    {{-- 🏠 EXISTING ADDRESS (DROPDOWN) --}}
    <div class="col-md-8 offset-md-2 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Saved Addresses</h5>

                @if($addresses->count())
                    <select class="form-select" id="addressDropdown" onchange="fillFromDropdown(this)">
                        <option value="">Select Saved Address</option>

                        @foreach($addresses as $addr)
                            <option
                                data-id="{{ $addr->id }}"
                                data-address="{{ $addr->address }}"
                                data-nearby="{{ $addr->nearby }}"
                                data-city="{{ $addr->city }}"
                                data-state="{{ $addr->state }}"
                                data-pincode="{{ $addr->pincode }}"
                            >
                                {{ $addr->address }}, {{ $addr->city }}
                            </option>
                        @endforeach
                    </select>

                    <div class="mt-2 text-muted small">
                        Selecting an address will auto-fill the form
                    </div>
                @else
                    <p class="text-muted mb-0">No saved addresses found</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ➕ ADD / USE ADDRESS FORM --}}
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Add / Use Address</h5>

                <form method="POST" action="{{ route('checkout.saveAddress') }}">
                    @csrf

                    {{-- ✅ EXISTING ADDRESS ID --}}
                    <input type="hidden" name="address_id" id="address_id">

                    <div class="mb-2">
                        <textarea
                            name="address"
                            id="address"
                            class="form-control"
                            placeholder="Full Address"
                            required
                            oninput="clearAddressId()"></textarea>
                    </div>

                    <div class="mb-2">
                        <input
                            type="text"
                            name="nearby"
                            id="nearby"
                            class="form-control"
                            placeholder="Nearby Landmark (optional)"
                            oninput="clearAddressId()">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input
                                type="text"
                                name="city"
                                id="city"
                                class="form-control"
                                placeholder="City"
                                required
                                oninput="clearAddressId()">
                        </div>

                        <div class="col-md-4 mb-2">
                            <input
                                type="text"
                                name="state"
                                id="state"
                                class="form-control"
                                placeholder="State"
                                required
                                oninput="clearAddressId()">
                        </div>

                        <div class="col-md-4 mb-2">
                            <input
                                type="text"
                                name="pincode"
                                id="pincode"
                                class="form-control"
                                placeholder="Pincode"
                                required
                                oninput="clearAddressId()">
                        </div>
                    </div>

                    <button class="btn btn-success w-100 mt-2">
                        Save & Continue to Payment
                    </button>

                </form>
            </div>
        </div>
    </div>

</div>
<a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
        ← Back to cart
    </a>
{{-- 🔥 AUTO FILL + SAFETY SCRIPT --}}
<script>
function fillFromDropdown(select) {
    let option = select.options[select.selectedIndex];

    document.getElementById('address_id').value =
        option.getAttribute('data-id') || '';

    document.getElementById('address').value =
        option.getAttribute('data-address') || '';

    document.getElementById('nearby').value =
        option.getAttribute('data-nearby') || '';

    document.getElementById('city').value =
        option.getAttribute('data-city') || '';

    document.getElementById('state').value =
        option.getAttribute('data-state') || '';

    document.getElementById('pincode').value =
        option.getAttribute('data-pincode') || '';
}

// 🔐 IMPORTANT: agar user manually kuch change kare
function clearAddressId() {
    document.getElementById('address_id').value = '';
}
</script>

@endsection
