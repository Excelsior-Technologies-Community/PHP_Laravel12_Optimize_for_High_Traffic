<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Register | Ecommerce Platform</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background: #ffffff;
            border-radius: 14px;
            width: 100%;
            max-width: 520px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .register-title {
            font-weight: 700;
            font-size: 24px;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn-register {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="register-card">

    <div class="mb-4">
        <h2 class="register-title">Create Admin Account</h2>
        <p class="text-muted small mb-0">
            Ecommerce Platform – Internal Use Only
        </p>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- NAME --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Email Address</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ old('email') }}"
                   required>
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   required>
        </div>

        {{-- CONFIRM PASSWORD --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Confirm Password</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   required>
        </div>

        {{-- ACTIONS --}}
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="small text-decoration-none">
                ← Back to Login
            </a>

            <button type="submit" class="btn btn-primary btn-register">
                Create Admin
            </button>
        </div>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
