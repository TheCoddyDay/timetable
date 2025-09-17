<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Timetable')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<!-- <body> -->
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('timetable.index') }}">
                <i class="fas fa-calendar-alt me-2"></i>Timetable System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('timetable.index') ? 'active' : '' }}" href="{{ route('timetable.index') }}">
                            <i class="fas fa-home me-1"></i>Today's Schedule
                        </a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('timetable.dashboard') ? 'active' : '' }}" href="{{ route('timetable.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
                        </a>
                    </li>
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('timetable.display') ? 'active' : '' }}" href="{{ route('timetable.display') }}">
                            <i class="fas fa-calendar-week me-1"></i>Weekly View
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item me-3 d-none d-lg-block">
                        <span class="navbar-text">
                            <i class="fas fa-clock me-1"></i>{{ now('Asia/Kolkata')->format('H:i:s') }}
                        </span>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="fas fa-right-to-bracket me-1"></i>Login
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-light">
                                    <i class="fas fa-right-from-bracket me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container flex-fill">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>


    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Timetable System</h5>
                    <p class="mb-0">A comprehensive timetable management system for educational institutions.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1">
                        <i class="fas fa-clock me-1"></i>
                        Current Time: {{ now('Asia/Kolkata')->format('d M Y, H:i:s') }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-calendar me-1"></i>
                        {{ now('Asia/Kolkata')->format('l') }}
                    </p>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} Timetable System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
