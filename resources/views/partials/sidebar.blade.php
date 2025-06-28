<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarLabel">
            @auth('admin')
                Admin Navigation
            @elseif (Auth::guard('web')->check())
                User Navigation
            @else
                Guest Navigation
            @endauth
        </h5>
        <button type="button" class="btn-close text-reset btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column pt-3">
        <ul class="nav flex-column mb-auto">

            {{-- Conditional Navigation Links --}}
            @auth('admin')
                {{-- Admin Links --}}
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('admin.dashboard') ? 'active bg-primary rounded' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-cogs me-2"></i> Admin Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('admin.customers.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('admin.customers.index') }}">
                        <i class="fas fa-users me-2"></i> Manage Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('admin.packages.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('admin.packages.index') }}">
                        <i class="fas fa-box-open me-2"></i> Manage Packages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('admin.bookings.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('admin.bookings.index') }}">
                        <i class="fas fa-calendar-check me-2"></i> View Bookings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('admin.invoices.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('admin.invoices.index') }}">
                        <i class="fas fa-file-invoice-dollar me-2"></i> View Invoices
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('admin.reports.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="fas fa-chart-line me-2"></i> Reports
                    </a>
                </li>
            @elseif (Auth::guard('web')->check())
                {{-- Regular User Links --}}
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('dashboard') ? 'active bg-primary rounded' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('bookings.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('bookings.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i> My Bookings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('invoices.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('invoices.index') }}">
                        <i class="fas fa-file-invoice me-2"></i> My Invoices
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('payments.*') ? 'active bg-primary rounded' : '' }}" href="{{ route('payments.index') }}">
                        <i class="fas fa-money-bill-wave me-2"></i> My Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('profile.edit') ? 'active bg-primary rounded' : '' }}" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-circle me-2"></i> Manage Account
                    </a>
                </li>
            @else
                {{-- Guest Links --}}
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('login') ? 'active bg-primary rounded' : '' }}" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::routeIs('register') ? 'active bg-primary rounded' : '' }}" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-2"></i> Register
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</div>
