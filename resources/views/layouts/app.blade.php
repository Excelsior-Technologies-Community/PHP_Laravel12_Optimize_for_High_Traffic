<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ecommerce Platform')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Premium Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
  /* ===============================
   GLOBAL BODY BACKGROUND
================================ */
body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    margin: 0;

    /* 🌟 Ecommerce Background Image */
    background: url('/images/photo.avif') center / cover no-repeat fixed;
}

/* 🔥 SOFT BLUR OVERLAY (NO WHITE LAYER) */
body::before {
    content: "";
    position: fixed;
    inset: 0;

    background: rgba(255, 255, 255, 0.18); /* very light tint */
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);

    z-index: -1;
}

/* ===============================
   AUTH CONTAINER
================================ */
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

/* ===============================
   AUTH CARD (GLASS EFFECT)
================================ */
.auth-box {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);

    border-radius: 26px;
    width: 100%;
    max-width: 920px;
    overflow: hidden;

    box-shadow:
        0 30px 80px rgba(0, 0, 0, 0.25),
        inset 0 0 0 1px rgba(255,255,255,0.3);
}

/* ===============================
   LEFT INFO PANEL
================================ */
.auth-left {
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.95),
        rgba(245,247,252,0.95)
    );
    padding: 64px;
}

.brand {
    font-size: 36px;
    font-weight: 800;
    color: #0d6efd;
    margin-bottom: 18px;
}

.auth-left p {
    color: #495057;
    font-size: 15px;
    line-height: 1.7;
}

.auth-left ul {
    padding-left: 18px;
    margin-top: 20px;
}

.auth-left li {
    margin-bottom: 10px;
    color: #343a40;
    font-size: 14px;
}

/* ===============================
   RIGHT FORM PANEL
================================ */
.auth-right {
    padding: 64px;
    background: rgba(255,255,255,0.98);
}

.auth-right h3 {
    font-weight: 700;
    margin-bottom: 6px;
}

.auth-right p {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 28px;
}

/* ===============================
   FORM ELEMENTS
================================ */
.form-control {
    border-radius: 14px;
    padding: 14px 16px;
    border: 1px solid #e1e5eb;
    font-size: 15px;
}

.form-control::placeholder {
    color: #adb5bd;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.12);
}

/* ===============================
   BUTTONS
================================ */
.btn {
    border-radius: 14px;
    padding: 14px;
    font-weight: 600;
    font-size: 15px;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7, #0a58ca);
}

/* ===============================
   SMALL LINKS
================================ */
.small-link {
    font-size: 14px;
}

.small-link a {
    text-decoration: none;
    font-weight: 500;
    color: #0d6efd;
}

.small-link a:hover {
    text-decoration: underline;
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 768px) {
    .auth-left {
        display: none;
    }

    .auth-right {
        padding: 42px 32px;
    }

    .auth-box {
        border-radius: 22px;
    }
}

    </style>
</head>

<body>

<div class="auth-container">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
