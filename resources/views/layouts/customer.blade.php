<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ecommerce Platform')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            padding-top: 80px;
            background-color: #f8f9fb;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        main {
            flex: 1;
        }

        /* =======================
           NAVBAR
        ======================== */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 22px;
            color: #0d6efd !important;
            letter-spacing: 0.4px;
        }

        .navbar .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 6px 14px;
        }

        .navbar .btn-outline-primary:hover {
            background: #0d6efd;
            color: #fff;
        }

        /* =======================
           PROFILE IMAGE
        ======================== */
        .profile-img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            transition: 0.2s;
        }

        .profile-img:hover {
            border-color: #0d6efd;
        }

        .dropdown-menu {
            border-radius: 14px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
        }

        /* =======================
           FOOTER
        ======================== */
        footer {
            background: linear-gradient(180deg, #ffffff, #f1f5f9);
            border-top: 1px solid #e5e7eb;
            color: #475569;
        }

        footer h4,
        footer h5 {
            color: #0f172a;
            font-weight: 700;
        }

        footer p {
            font-size: 14px;
            line-height: 1.6;
        }

        footer a {
            color: #475569;
            text-decoration: none;
            transition: 0.2s;
            font-size: 14px;
        }

        footer a:hover {
            color: #0d6efd;
            text-decoration: underline;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-bottom {
            font-size: 13px;
            color: #64748b;
        }
        .footer-link {
    font-size: 19px;
    color:rgb(10, 112, 255);
    text-decoration: none;
    transition: 0.2s;
  
}

.footer-link:hover {
    color: #0d6efd;
    text-decoration: underline;
}

    </style>
</head>

<body>

{{-- 🔹 NAVBAR --}}
<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="{{ route('customer.products') }}">
             Ecommerce Platform
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">

            @auth('customer')
                <a href="{{ route('customer.products') }}" class="btn btn-outline-primary btn-sm">
                    Products
                </a>

                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm">
                    Cart
                </a>

                <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary btn-sm">
                    Orders
                </a>

                {{-- PROFILE --}}
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown">
                        <img
                            src="{{ auth('customer')->user()->profile_image
                                    ? asset('images/'.auth('customer')->user()->profile_image)
                                    : asset('images/default-user.png') }}"
                            class="profile-img">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                 My Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('customer.logout') }}">
                                 Logout
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('customer.login') }}" class="btn btn-primary btn-sm">
                    Login
                </a>
            @endauth

            <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">
                Admin Panel
            </a>
        </div>
    </div>
</nav>

{{-- 🔹 PAGE CONTENT --}}
<main class="container my-4">
    @yield('content')
</main>

{{-- 🔹 FOOTER --}}
<footer class="pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row gy-4">

          <div class="col-md-4">
    <h4> Ecommerce Platform</h4>

    <div class="d-flex gap-3 mt-2 flex-wrap">
        <a href="{{ route('about') }}" class="footer-link">About Us</a>
        <a href="{{ route('privacy') }}" class="footer-link">Privacy</a>
        <a href="{{ route('terms') }}" class="footer-link">Terms</a>
    </div>
</div>


            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled footer-links mt-2">
                    <li><a href="{{ route('customer.products') }}">Products</a></li>
                    <li><a href="{{ route('customer.orders') }}">My Orders</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="{{ route('customer.profile') }}">My Profile</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5>Support</h5>
                <p class="small mb-1">📧 support@ecommerceplatform.com</p>
                <p class="small mb-1">📞 +91 99999 88888</p>
                <p class="small mb-1">
                    📍 Sindhu Bhavan Road, Ahmedabad – 395002
                </p>
            </div>
        </div>

        <hr class="my-4">

        <div class="text-center footer-bottom">
            © {{ date('Y') }} Ecommerce Platform. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- AUTO LOGOUT --}}
@auth('customer')
<script>
    window.addEventListener('unload', function () {
        navigator.sendBeacon("{{ route('customer.auto.logout') }}");
    });
</script>
@endauth

</body>
</html>
