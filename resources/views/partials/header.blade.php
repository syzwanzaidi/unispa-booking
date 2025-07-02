<header class="fixed-header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                <i class="fas fa-bars"></i>
            </button>

           @if (Auth::guard('admin')->check())
                <a class="navbar-brand me-auto" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo-unispa.png') }}" alt="UniSPA Admin Logo" style="height: 50px;">
                    Admin Panel
                </a>
            @elseif (Auth::guard('web')->check())
                <a class="navbar-brand me-auto" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo-unispa.png') }}" alt="UniSPA Logo" style="height: 50px;">
                </a>
            @else
                <a class="navbar-brand me-auto" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo-unispa.png') }}" alt="UniSPA Logo" style="height: 50px;">
                </a>
            @endif
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">Our Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a>
                    </li>
                    @auth('admin')
                        {{-- Admin Logout --}}
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger ms-lg-2">Logout (Admin)</button>
                            </form>
                        </li>
                    @else
                        @auth('web')
                            {{-- User Logout --}}
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger ms-lg-2">Logout</button>
                                </form>
                            </li>
                        @else
                            {{-- Guest Links --}}
                            <li class="nav-item">
                                <a class="btn btn-primary me-2 ms-lg-2" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-light" href="{{ route('register') }}">Register</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
