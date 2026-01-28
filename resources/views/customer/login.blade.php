@extends('layouts.app')

@section('title','Login')

@section('content')

<div class="auth-box row g-0">

    {{-- LEFT --}}
    <div class="col-md-6 auth-left d-flex flex-column justify-content-center">
        <div class="brand mb-3">EcommercePlatform</div>
        <p class="text-muted">
            Welcome back! Login to manage your orders,
            cart and profile.
        </p>
    </div>

    {{-- RIGHT --}}
    <div class="col-md-6 auth-right">
        <h3 class="mb-1">Welcome Back</h3>
        <p class="text-muted small mb-4">Login to continue</p>

        @if ($errors->any())
            <div class="alert alert-danger small">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login.post') }}">
            @csrf

            <div class="mb-3">
                <input type="email" name="email" class="form-control"
                       placeholder="Email Address" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password"
                       class="form-control"
                       placeholder="Password" required>
            </div>

            <button class="btn btn-success w-100 mt-2">
                Login
            </button>

            <div class="text-center mt-3 small-link">
                <span class="text-muted">New here?</span>
                <a href="{{ route('customer.register') }}">Create account</a>
            </div>
        </form>
    </div>

</div>

@endsection
