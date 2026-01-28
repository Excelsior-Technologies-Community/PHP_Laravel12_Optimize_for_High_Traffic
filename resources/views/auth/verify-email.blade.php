<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification | Ecommerce Platform</title>

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
        .verify-card {
            background: #ffffff;
            max-width: 540px;
            width: 100%;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.08);
        }
        .verify-title {
            font-weight: 700;
            font-size: 22px;
        }
        .btn {
            border-radius: 10px;
            height: 46px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="verify-card">

    <h2 class="verify-title mb-3">Verify Your Email</h2>

    <p class="text-muted">
        Thanks for signing up! Please verify your email address by clicking
        the link we sent to your inbox.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-4 gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Resend Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                Logout
            </button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
