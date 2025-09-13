
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'POS System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/paginate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/layout.css') }}" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="p-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="fas fa-cash-register me-2"></i>
                POS System
            </a>
        </div>
        
        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                    <i class="fas fa-shopping-cart me-2"></i>Point of Sale
                </a>
            </li>
           <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fas fa-tags me-2"></i>Categories
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="fas fa-box me-2"></i>Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <i class="fas fa-users me-2"></i>Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                    <i class="fas fa-chart-line me-2"></i>Sales
                </a>
            </li>


         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-users me-2"></i>Users
            </a>
        </li>

            
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                <i class="fas fa-users-cog me-2"></i>Roles
            </a>
        </li>


        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::user()->name ?? 'User' }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') ?? '#' }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>


        <footer class="bg-light border-top py-3 mt-auto">
            <div class="container text-center">
                <p class="mb-1">
                    Designed & Developed by <strong>
                        <a href="https://www.linkedin.com/in/mohamed-hamed-b5b1b724b/" target="_blank"> Mohamed Hamed </a></strong>
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="mailto:muhmdhamed757@gmail.com" class="text-decoration-none text-dark">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                    <a href="tel:+201029288339" class="text-decoration-none text-dark">
                        <i class="fas fa-phone"></i> +201029288339
                    </a>
                    <a href="https://github.com/mohamid9" target="_blank" class="text-decoration-none text-dark">
                        <i class="fab fa-github"></i> GitHub
                    </a>
                    <a href="https://www.linkedin.com/in/mohamed-hamed-b5b1b724b/" target="_blank" class="text-decoration-none text-dark">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                </div>
            </div>
        </footer>
    </main>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bundle.min.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>