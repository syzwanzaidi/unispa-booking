<header class="fixed-header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">UniSPA</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        {{-- Homepage link --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>

                        {{-- !!! ADD THIS LINK FOR PACKAGES !!! --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('packages.index') }}">Our Services</a>
                        </li>

                        {{-- Conditional links for authenticated users (Dashboard, Logout) --}}
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-white">Logout</button>
                                </form>
                            </li>
                        @else
                            {{-- Links for guests (Login, Register) --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
