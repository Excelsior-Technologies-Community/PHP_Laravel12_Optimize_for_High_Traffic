@extends('layouts.customer')

@section('title','My Profile')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">

                <h3 class="mb-4 fw-bold text-center">My Profile</h3>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('customer.profile.update') }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- PROFILE IMAGE --}}
                    <div class="text-center mb-4">
                        <img src="{{ $customer->profile_image
                                ? asset('images/'.$customer->profile_image)
                                : asset('images/default-user.png') }}"
                             class="rounded-circle shadow"
                             width="150"
                             height="150"
                             style="object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Profile Image</label>
                        <input type="file"
                               name="profile_image"
                               class="form-control">
                    </div>

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ $customer->name }}"
                               required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email"
                               class="form-control"
                               value="{{ $customer->email }}"
                               disabled>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <button class="btn btn-primary w-100 mb-3">
                        Update Profile
                    </button>
                </form>

                <hr>

                {{-- QUICK ACTIONS --}}
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <a href="{{ route('customer.orders') }}"
                       class="btn btn-outline-primary w-100 w-md-auto">
                         My Orders
                    </a>

                    <a href="{{ route('cart.index') }}"
                       class="btn btn-outline-success w-100 w-md-auto">
                        View Cart
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
