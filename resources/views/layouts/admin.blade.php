<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- META --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- BOOTSTRAP CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- SELECT2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    {{-- VITE (TAILWIND + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- PAGE LEVEL CSS --}}
    @stack('styles')
</head>

<body class="bg-light">

    {{-- ✅ ADMIN NAVIGATION --}}
    @include('layouts.navigation')

    {{-- 🔔 FLASH MESSAGES --}}
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- 📦 MAIN CONTENT --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- FOOTER (OPTIONAL) --}}

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- SELECT2 GLOBAL INIT --}}
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select options',
                allowClear: true
            });
        });
    </script>

    {{-- ORDER VIEW TOGGLE (ADMIN ORDERS) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.view-order').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const targetRow = document.getElementById(targetId);

                    if (!targetRow) return;

                    document.querySelectorAll('.order-details').forEach(row => {
                        if (row !== targetRow) row.classList.add('d-none');
                    });

                    targetRow.classList.toggle('d-none');
                });
            });
        });
    </script>

    {{-- PAGE LEVEL SCRIPTS --}}
    @stack('scripts')

</body>
</html>
