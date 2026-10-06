<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Evaluasi Kinerja SI-IMUT')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #4f46e5;
            --brand-soft: #eef2ff;
            --bg-app: #f6f7fb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-app);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--brand) !important;
        }

        .nav-link.active {
            color: var(--brand) !important;
            font-weight: 600;
        }

        .card-panel,
        .card-stat {
            border: 1px solid #eef0f3;
            border-radius: 14px;
            background: #fff;
        }
    </style>
    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
                <i class="bi bi-clipboard2-data-fill"></i> SI-IMUT Eval
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ route('landing') }}"
                            class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>

</html>
