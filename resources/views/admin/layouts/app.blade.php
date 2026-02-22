<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('language.supported.' . app()->getLocale() . '.dir', 'ltr') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - {{ __('dashboard.sidebar.dashboard') }}</title>

    {{-- Load Bootstrap CSS based on text direction (RTL/LTR) --}}
    @if (config('language.supported.' . app()->getLocale() . '.dir') == 'rtl')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sidebar-width: 250px;
        }

        body {
            background-color: #f4f6f9;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        #sidebar {
            width: var(--sidebar-width);
            background: #343a40;
            color: white;
            min-height: 100vh;
            transition: all 0.3s;
        }

        #sidebar .nav-link {
            color: #c2c7d0;
            margin: 5px 10px;
            border-radius: 5px;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        #sidebar .nav-link i {
            margin-inline-end: 10px;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        footer {
            background: white;
            border-top: 1px solid #dee2e6;
            padding: 15px 0;
        }

        .nav-header {
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: #6c757d;
            padding: 10px 20px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    {{-- Top Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">{{ __('dashboard.sidebar.dashboard') }}</a>

            <div class="ms-auto d-flex align-items-center">
                {{-- Language Switcher Dropdown --}}
                <div class="dropdown me-3">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        {{ config('language.supported.' . app()->getLocale() . '.name') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach (config('language.supported') as $langKey => $langData)
                            <li><a class="dropdown-item"
                                    href="{{ route('switch.language', $langKey) }}">{{ $langData['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- User Profile Menu --}}
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#"
                        role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5 me-2"></i>
                        <span class="fw-bold">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile.edit') }}">
                                <i class="bi bi-person-gear me-2"></i> {{ __('dashboard.profile.settings') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('dashboard.general.close') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        {{-- Side Navigation Menu --}}
        <nav id="sidebar">
            <div class="p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> {{ __('dashboard.sidebar.home') }}
                        </a>
                    </li>

                    {{-- Section: User Management --}}
                    <li class="nav-header">
                        {{ __('dashboard.sidebar.users_management') }}
                    </li>

                    @role('Super Admin')
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}"
                                class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <i class="bi bi-key"></i> {{ __('dashboard.sidebar.Permissions') }}
                            </a>
                        </li>
                    @endrole

                    @can('manage-users')
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i> {{ __('dashboard.sidebar.staff') }}
                            </a>
                        </li>
                    @endcan

                    {{-- Role Management Permission --}}
                    @can('manage-roles')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}"
                                class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="bi bi-shield-lock"></i> {{ __('dashboard.sidebar.roles') }}
                            </a>
                        </li>
                    @endcan

                    <li class="nav-item">
                        <a href="{{ route('admin.pages.index') }}"
                            class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text"></i> {{ __('dashboard.pages.title') }}
                        </a>
                    </li>

                    {{-- Section: Store Management --}}
                    <li class="nav-header">
                        {{ __('dashboard.sidebar.store_settings') }}
                    </li>

                    {{-- Category Management --}}
                    @can('manage-categories')
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}"
                                class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="bi bi-tags"></i> {{ __('dashboard.categories.title') }}
                            </a>
                        </li>
                    @endcan

                    {{-- Product Management --}}
                    @can('manage-products')
                        <li class="nav-item">
                            <a href="{{ route('admin.products.index') }}"
                                class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam"></i> {{ __('dashboard.products.title') }}
                            </a>
                        </li>
                    @endcan

                    {{-- Site Settings --}}
                    @can('manage-settings')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}"
                                class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="bi bi-gear"></i> {{ __('dashboard.permissions.manage-settings') }}
                            </a>
                        </li>
                    @endcan

                    {{-- Customer Management --}}
                    @can('view-customers')
                        <li class="nav-item">
                            <a href="{{ route('admin.customers.index') }}"
                                class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                                <i class="bi bi-people"></i> {{ __('dashboard.customers.title') }}
                            </a>
                        </li>
                    @endcan

                    {{-- Order Management --}}
                    @can('view-orders')
                        <li class="nav-item">
                            <a href="{{ route('admin.orders.index') }}"
                                class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <i class="bi bi-cart-check"></i> {{ __('dashboard.orders.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </nav>

        {{-- Main Content Area --}}
        <main>
            <div class="container-fluid">
                {{-- Global Success Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    {{-- Footer Section --}}
    <footer class="text-center">
        &copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>.
        {{ __('dashboard.all_rights_reserved') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
