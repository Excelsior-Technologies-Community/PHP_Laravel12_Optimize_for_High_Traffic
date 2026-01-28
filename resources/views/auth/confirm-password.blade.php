<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Password | Ecommerce Platform</title>

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

        .confirm-card {
            background: #ffffff;
            border-radius: 14px;
            width: 100%;
            max-width: 480px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        .confirm-title {
            font-weight: 700;
            font-size: 22px;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn-confirm {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="confirm-card">

    <div class="mb-4">
        <h2 class="confirm-title">Confirm Password</h2>
        <p class="text-muted small mb-0">
            This action requires password confirmation for security.
        </p>
    </div>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- PASSWORD --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   required
                   autocomplete="current-password">
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ url()->previous() }}" class="small text-decoration-none">
                ← Go Back
            </a>

            <button type="submit" class="btn btn-primary btn-confirm">
                Confirm
            </button>
        </div>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
