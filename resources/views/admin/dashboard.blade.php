@extends('layouts.admin')

@section('content')

<style>
    .admin-dashboard-card {
        border-radius: 16px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        padding: 30px;
    }

    .admin-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        font-weight: 700;
        margin: 0 auto 15px;
    }

    .dashboard-btn {
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
    }

    .dashboard-btn i {
        margin-right: 6px;
    }
</style>

<div class="row justify-content-center mt-4">
    <div class="col-md-6 col-lg-5">

        <div class="admin-dashboard-card text-center">

            {{-- 👤 AVATAR --}}
            <div class="admin-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            {{-- 👋 WELCOME --}}
            <h4 class="fw-bold mb-1">
                Welcome, {{ auth()->user()->name }}
            </h4>

            <p class="text-muted mb-3">
                {{ auth()->user()->email }}
            </p>

            <hr class="mb-4">

            {{-- 🚀 ACTION BUTTONS --}}
            <div class="d-grid gap-3">

                <a href="{{ route('products.index') }}"
                   class="btn btn-primary dashboard-btn">
                    🛒 Manage Products
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="btn btn-outline-dark dashboard-btn">
                    📦 View Orders
                </a>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="btn btn-link text-decoration-none text-primary">
                        🔐 Forgot Password?
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger dashboard-btn">
                        🚪 Logout
                    </button>
                </form>

            </div>

        </div>

    </div>
</div>

@endsection

