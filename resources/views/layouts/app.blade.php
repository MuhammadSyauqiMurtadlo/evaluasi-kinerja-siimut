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
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-app);
        }

        .sidebar {
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #eef0f3;
        }

        .sidebar .brand {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--brand);
        }

        .sidebar .nav-link {
            color: #4b5563;
            border-radius: 8px;
            padding: .6rem .9rem;
            font-weight: 500;
            font-size: .92rem;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 8px;
        }

        .sidebar .nav-link.active {
            background: var(--brand-soft);
            color: var(--brand);
        }

        .sidebar .nav-link:hover:not(.active) {
            background: #f9fafb;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #eef0f3;
        }

        .card-stat {
            border: 1px solid #eef0f3;
            border-radius: 14px;
            background: #fff;
        }

        .card-stat .icon-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .card-panel {
            border: 1px solid #eef0f3;
            border-radius: 14px;
            background: #fff;
        }

        .badge-severity-low {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-severity-medium {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-severity-high {
            background: #fed7aa;
            color: #9a3412;
        }

        .badge-severity-critical {
            background: #fecaca;
            color: #991b1b;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar p-3" style="width: 240px; position: sticky; top: 0;">
            <div class="brand d-flex align-items-center gap-2 mb-4 px-2">
                <i class="bi bi-clipboard2-data-fill fs-4"></i>
                <span>SI-IMUT Eval</span>
            </div>
            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('evaluasi.index') }}"
                        class="nav-link {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i> Evaluasi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('analisis.index') }}"
                        class="nav-link {{ request()->routeIs('analisis.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i> Analisis
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.index') }}"
                        class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-pdf"></i> Laporan
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main -->
        <div class="flex-grow-1">
            <div class="topbar px-4 py-3 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h5>
                <span class="text-muted small">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>

            <div class="p-4">
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>

</html>
