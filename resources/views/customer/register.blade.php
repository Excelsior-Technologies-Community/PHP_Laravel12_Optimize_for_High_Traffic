@extends('layouts.app')

@section('title','Create Account')

@section('content')

<div class="auth-box row g-0">

    {{-- LEFT --}}
    <div class="col-md-6 auth-left d-flex flex-column justify-content-center">
        <div class="brand mb-3">EcommercePlatform</div>
        <p class="text-muted">
            Join us and experience seamless shopping, secure payments,
            and fast delivery — all in one place.
        </p>

        <ul class="text-muted small mt-3">
            <li>✔ Secure checkout</li>
            <li>✔ Track your orders</li>
            <li>✔ Easy returns</li>
        </ul>
    </div>

    {{-- RIGHT --}}
    <div class="col-md-6 auth-right">
        <h3 class="mb-1">Create Account</h3>
        <p class="text-muted small mb-4">Get started in less than a minute</p>

        @if ($errors->any())
            <div class="alert alert-danger small">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.register.post') }}">
            @csrf

            <div class="mb-3">
                <input type="text" name="name" class="form-control"
                       placeholder="Full Name" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control"
                       placeholder="Email Address" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control"
                       placeholder="Password" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Confirm Password" required>
            </div>

            <button class="btn btn-primary w-100 mt-2">
                Create Account
            </button>

            <div class="text-center mt-3 small-link">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('customer.login') }}">Login</a>
            </div>
        </form>
    </div>

</div>

@endsection
