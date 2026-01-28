<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Ecommerce Platform</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
          
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
        }

        .login-title {
            font-weight: 700;
            font-size: 26px;
        }

        .login-subtitle {
            font-size: 14px;
            color: #6c757d;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn-login {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="text-center mb-4">
        <h2 class="login-title">Admin Login</h2>
        <p class="login-subtitle">Ecommerce Platform Control Panel</p>
    </div>

    {{-- STATUS MESSAGE --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Email Address</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   placeholder="admin@example.com"
                   value="{{ old('email') }}"
                   required autofocus>
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Enter password"
                   required>
        </div>

        {{-- REMEMBER --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">
                Remember me
            </label>
        </div>

        {{-- LOGIN BUTTON --}}
        <button type="submit" class="btn btn-primary w-100 btn-login">
            Login to Admin Panel
        </button>

        {{-- FORGOT PASSWORD --}}
        @if (Route::has('password.request'))
            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="small text-decoration-none">
                    Forgot password?
                </a>
            </div>
        @endif
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
