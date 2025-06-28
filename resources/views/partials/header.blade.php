<header class="fixed-header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                @if (Auth::guard('admin')->check())
                    {{-- If Admin is logged in --}}
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">UniSPA Admin Panel</a>
                @elseif (Auth::guard('web')->check())
                    {{-- If Regular User is logged in --}}
                    <a class="navbar-brand" href="{{ route('dashboard') }}">UniSPA</a>
                @else
                    {{-- If Guest --}}
                    <a class="navbar-brand" href="{{ url('/') }}">UniSPA</a>
                @endif

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @if (Auth::guard('admin')->check())
                            {{-- ADMIN Navigation Items --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">Our Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">Manage Customers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/packages*') ? 'active' : '' }}" href="{{ route('admin.packages.index') }}">Manage Packages</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/bookings*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">View Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Reports</a>
                            </li>

                            {{-- Admin Logout --}}
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-white">Logout (Admin)</button>
                                </form>
                            </li>

                        @elseif (Auth::guard('web')->check())
                            {{-- REGULAR USER Navigation Items --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">Our Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">My Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('bookings*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">My Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('invoices*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">My Invoices</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('payments*') ? 'active' : '' }}" href="{{ route('payments.index') }}">My Payments</a>
                            </li>

                            {{-- User Logout --}}
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-white">Logout</button>
                                </form>
                            </li>

                        @else
                            {{-- GUEST Navigation Items --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">Our Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
