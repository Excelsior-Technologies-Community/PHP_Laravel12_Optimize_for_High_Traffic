<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Ecommerce Platform</title>

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
        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 520px;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.08);
        }
        .auth-title {
            font-weight: 700;
            font-size: 22px;
        }
        .form-control {
            height: 48px;
            border-radius: 10px;
        }
        .btn-primary {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="auth-card">

    <div class="mb-4">
        <h2 class="auth-title">Reset Password</h2>
        <p class="text-muted small mb-0">
            Create a new password for your admin account.
        </p>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ old('email', $request->email) }}"
                   required>
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">New Password</label>
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

        <button type="submit" class="btn btn-primary w-100">
            Reset Password
        </button>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
